<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\ScheduleService;

class ScheduleController extends BaseController
{
    private ScheduleService $scheduleService;

    private const FILTER_RULES = [
        'search' => [
            'rules' => 'permit_empty|max_length[100]',
        ],
        'day' => [
            'rules' => 'permit_empty|in_list[MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY,SUNDAY]',
        ],
        'gender' => [
            'rules' => 'permit_empty|in_list[ALL,MALE,FEMALE]',
        ],
        'access_type' => [
            'rules' => 'permit_empty|in_list[ALL,MEMBER,CASUAL]',
        ],
        'status' => [
            'rules' => 'permit_empty|in_list[ACTIVE,INACTIVE]',
        ],
        'page' => [
            'rules' => 'permit_empty|integer|greater_than_equal_to[1]',
        ],
        'limit' => [
            'rules' => 'permit_empty|integer|in_list[10,25,50,100]',
        ],
    ];

    private const FORM_RULES = [
        'name' => [
            'rules' => 'required|max_length[100]',
            'errors' => [
                'required' => 'Schedule name is required.',
                'max_length' => 'Schedule name cannot exceed 100 characters.',
            ],
        ],
        'day_of_week' => [
            'rules' => 'required|in_list[MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY,SUNDAY]',
            'errors' => [
                'required' => 'Schedule day is required.',
                'in_list' => 'Invalid schedule day.',
            ],
        ],
        'start_time' => [
            'rules' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]',
            'errors' => [
                'required' => 'Start time is required.',
                'regex_match' => 'Start time must use HH:MM format.',
            ],
        ],
        'end_time' => [
            'rules' => 'required|regex_match[/^(?:[01]\d|2[0-3]):[0-5]\d$/]',
            'errors' => [
                'required' => 'End time is required.',
                'regex_match' => 'End time must use HH:MM format.',
            ],
        ],
        'allowed_gender' => [
            'rules' => 'required|in_list[ALL,MALE,FEMALE]',
            'errors' => [
                'required' => 'Allowed gender is required.',
                'in_list' => 'Invalid allowed gender.',
            ],
        ],
        'access_type' => [
            'rules' => 'required|in_list[ALL,MEMBER,CASUAL]',
            'errors' => [
                'required' => 'Access type is required.',
                'in_list' => 'Invalid access type.',
            ],
        ],
        'capacity_limit' => [
            'rules' => 'required|integer|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Capacity limit is required.',
                'integer' => 'Capacity limit must be a number.',
                'greater_than_equal_to' => 'Capacity limit cannot be negative.',
            ],
        ],
        'priority' => [
            'rules' => 'required|integer|greater_than_equal_to[0]',
            'errors' => [
                'required' => 'Priority is required.',
                'integer' => 'Priority must be a number.',
                'greater_than_equal_to' => 'Priority cannot be negative.',
            ],
        ],
        'is_active' => [
            'rules' => 'permit_empty|in_list[0,1]',
            'errors' => [
                'in_list' => 'Invalid schedule status.',
            ],
        ],
    ];

    public function __construct()
    {
        $this->scheduleService = new ScheduleService();
    }

    public function index()
    {
        $query = $this->request->getGet();

        if (!$this->validateData($query, self::FILTER_RULES)) {
            return redirect()
                ->to('/admin/schedule')
                ->with('error', 'Invalid schedule filter.');
        }

        $page = max(1, (int) ($query['page'] ?? 1));
        $limit = (int) ($query['limit'] ?? 10);

        $filters = [
            'search' => trim((string) ($query['search'] ?? '')),
            'day' => strtoupper(trim((string) ($query['day'] ?? ''))),
            'gender' => strtoupper(trim((string) ($query['gender'] ?? ''))),
            'access_type' => strtoupper(
                trim((string) ($query['access_type'] ?? ''))
            ),
            'status' => strtoupper(trim((string) ($query['status'] ?? ''))),
        ];

        $result = $this->scheduleService->index(
            filters: $filters,
            limit: $limit,
            page: $page,
        );

        if (!$result['ok']) {
            session()->setFlashdata(
                'error',
                $this->errorMessage($result['error'])
            );
        }

        $data = $result['data'] ?? [
            'schedules' => [],
            'summary' => $this->emptySummary(),
            'pager' => null,
            'page' => $page,
            'limit' => $limit,
        ];

        return view('pages/admin/schedule/index', [
            'active' => 'schedule',
            'schedules' => $data['schedules'] ?? [],
            'summary' => $data['summary'] ?? $this->emptySummary(),
            'pager' => $data['pager'] ?? null,
            'page' => $data['page'] ?? $page,
            'limit' => $data['limit'] ?? $limit,
        ]);
    }

    public function store()
    {
        $post = $this->request->getPost();

        /*
         * Checkbox yang tidak dicentang tidak dikirim browser.
         * Kita normalize supaya validation dan service menerima 0 atau 1.
         */
        $post['is_active'] = isset($post['is_active']) ? '1' : '0';

        if (!$this->validateData($post, self::FORM_RULES)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $result = $this->scheduleService->create($post);

        if (!$result['ok']) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $this->errorMessage($result['error'])
                );
        }

        return redirect()
            ->to('/admin/schedule')
            ->with('success', 'Access schedule created successfully.');
    }

    public function update(int $id)
    {
        if ($id <= 0) {
            return redirect()
                ->to('/admin/schedule')
                ->with('error', 'Invalid access schedule ID.');
        }

        $post = $this->request->getPost();
        $post['is_active'] = isset($post['is_active']) ? '1' : '0';

        if (!$this->validateData($post, self::FORM_RULES)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $result = $this->scheduleService->update($id, $post);

        if (!$result['ok']) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $this->errorMessage($result['error'])
                );
        }

        return redirect()
            ->to('/admin/schedule')
            ->with('success', 'Access schedule updated successfully.');
    }

    public function changeStatus(int $id)
    {
        if ($id <= 0) {
            return redirect()
                ->to('/admin/schedule')
                ->with('error', 'Invalid access schedule ID.');
        }

        $post = $this->request->getPost();

        $rules = [
            'is_active' => [
                'rules' => 'required|in_list[0,1]',
                'errors' => [
                    'required' => 'Schedule status is required.',
                    'in_list' => 'Invalid schedule status.',
                ],
            ],
        ];

        if (!$this->validateData($post, $rules)) {
            return redirect()
                ->back()
                ->with('errors', $this->validator->getErrors());
        }

        $isActive = $post['is_active'] === '1';

        $result = $this->scheduleService->changeStatus(
            id: $id,
            isActive: $isActive,
        );

        if (!$result['ok']) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    $this->errorMessage($result['error'])
                );
        }

        $message = $isActive
            ? 'Access schedule activated successfully.'
            : 'Access schedule disabled successfully.';

        return redirect()
            ->back()
            ->with('success', $message);
    }

    private function errorMessage(mixed $error): string
    {
        if (is_array($error)) {
            return implode(' ', array_map(
                static fn ($message): string => (string) $message,
                $error
            ));
        }

        return (string) ($error ?: 'Schedule operation failed.');
    }

    private function emptySummary(): array
    { 
        return [
            'active' => 0,
            'female' => 0,
            'member' => 0,
            'unlimited' => 0,  
            ];
    }

}