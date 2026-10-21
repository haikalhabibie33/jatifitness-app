<?php

namespace App\Services;

use App\Contracts\Repositories\AccessScheduleRepositoryInterface;
use App\Repositories\AccessScheduleRepository;
use App\Traits\RepositoryResponseTrait;

class ScheduleService
{
    use RepositoryResponseTrait;

    private AccessScheduleRepositoryInterface $accessScheduleRepository;

    public function __construct(
        ?AccessScheduleRepositoryInterface $accessScheduleRepository = null
    ) {
        $this->accessScheduleRepository =
            $accessScheduleRepository ?? new AccessScheduleRepository();
    }

    public function index(
        array $filters,
        int $limit = 10,
        int $page = 1
    ): array {
        $scheduleResult = $this->accessScheduleRepository->findAll(
            filters: $filters,
            limit: $limit,
            page: $page,
        );

        if (!$scheduleResult['ok']) {
            return $this->failedResponse(
                $scheduleResult['error'],
                [
                    'schedules' => [],
                    'pager' => null,
                    'summary' => $this->emptySummary(),
                    'page' => $page,
                    'limit' => $limit,
                ]
            );
        }

        $summaryResult = $this->accessScheduleRepository->getSummary();

        $summary = $summaryResult['ok']
            ? $summaryResult['data']
            : $this->emptySummary();

        return $this->successResponse([
            'schedules' => $scheduleResult['data']['schedules'],
            'pager' => $scheduleResult['data']['pager'],
            'summary' => $summary,
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    public function create(array $payload): array
    {
        $normalized = $this->normalizePayload($payload);

        $validation = $this->validateScheduleTime(
            $normalized['start_time'],
            $normalized['end_time'],
        );

        if (!$validation['ok']) {
            return $validation;
        }

        return $this->accessScheduleRepository->create($normalized);
    }

    public function update(int $id, array $payload): array
    {
        if ($id <= 0) {
            return $this->failedResponse(
                'Invalid access schedule ID.',
                null
            );
        }

        $existingResult = $this->accessScheduleRepository->findById($id);

        if (!$existingResult['ok']) {
            return $existingResult;
        }

        $normalized = $this->normalizePayload($payload);

        $validation = $this->validateScheduleTime(
            $normalized['start_time'],
            $normalized['end_time'],
        );

        if (!$validation['ok']) {
            return $validation;
        }

        return $this->accessScheduleRepository->update(
            $id,
            $normalized
        );
    }

    public function changeStatus(int $id, bool $isActive): array
    {
        if ($id <= 0) {
            return $this->failedResponse(
                'Invalid access schedule ID.',
                null
            );
        }

        return $this->accessScheduleRepository->changeStatus(
            $id,
            $isActive
        );
    }

    public function findById(int $id): array
    {
        if ($id <= 0) {
            return $this->failedResponse(
                'Invalid access schedule ID.',
                null
            );
        }

        return $this->accessScheduleRepository->findById($id);
    }

    private function normalizePayload(array $payload): array
    {
        return [
            'name' => trim((string) ($payload['name'] ?? '')),

            'day_of_week' => strtoupper(
                trim((string) ($payload['day_of_week'] ?? ''))
            ),

            'start_time' => $this->normalizeTime(
                (string) ($payload['start_time'] ?? '')
            ),

            'end_time' => $this->normalizeTime(
                (string) ($payload['end_time'] ?? '')
            ),

            'allowed_gender' => strtoupper(
                trim((string) ($payload['allowed_gender'] ?? 'ALL'))
            ),

            'access_type' => strtoupper(
                trim((string) ($payload['access_type'] ?? 'ALL'))
            ),

            'capacity_limit' => max(
                0,
                (int) ($payload['capacity_limit'] ?? 0)
            ),

            'priority' => max(
                0,
                (int) ($payload['priority'] ?? 0)
            ),

            'is_active' => !empty($payload['is_active']) ? 1 : 0,
        ];
    }

    private function normalizeTime(string $time): string
    {
        $time = trim($time);

        if ($time === '') {
            return '';
        }

        if (preg_match('/^\d{2}:\d{2}$/', $time)) {
            return $time . ':00';
        }

        return $time;
    }

    private function validateScheduleTime(
        string $startTime,
        string $endTime
    ): array {
        if ($startTime === '' || $endTime === '') {
            return $this->failedResponse(
                'Start time and end time are required.',
                null
            );
        }

        $startTimestamp = strtotime('1970-01-01 ' . $startTime);
        $endTimestamp = strtotime('1970-01-01 ' . $endTime);

        if ($startTimestamp === false || $endTimestamp === false) {
            return $this->failedResponse(
                'Invalid schedule time format.',
                null
            );
        }

        if ($startTimestamp >= $endTimestamp) {
            return $this->failedResponse(
                'End time must be later than start time.',
                null
            );
        }

        return $this->successResponse(true);
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