<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\Access\CheckinService;
use App\Traits\RequestValidationTrait;
use App\DTO\Checkin\AccessHistoryQueryDto;

class CheckinListController extends BaseController
{
    use RequestValidationTrait;
    private CheckinService $checkinService;

    public function __construct()
    {
        $this->checkinService = new CheckinService();
    }

    private const RULES = [
    'search' => 'permit_empty|max_length[100]|regex_match[/^[a-zA-Z0-9\s@._+\-()]+$/]',
    'type' => 'permit_empty|in_list[ALL,MEMBER,CASUAL]',
    'from' => 'permit_empty|valid_date[Y-m-d]',
    'to' => 'permit_empty|valid_date[Y-m-d]',
];

    public function index()
    {
        $query = $this->request->getGet();
        if (!$this->validate(self::RULES, $query)) {
            return redirect()
                ->back()
                ->with('error', 'invalid request!');
        }
        $dto = AccessHistoryQueryDto::fromQuery($query);
        $result = $this->checkinService->history($dto);
        // dd($result['data']['histories']);
        return view('pages/admin/check-in-list/index',[
            'active' => 'checkin-list',
            'data' => $result['data']['histories'],
            'pager' => $result['data']['pager'],
            'total' => $result['data']['total'],
            'page' => $result['data']['page'],
            'limit' => $result['data']['limit'],
        ]);
    }
}