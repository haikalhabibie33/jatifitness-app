<?php 

namespace App\Controllers\Testing;

use App\Controllers\BaseController;
use App\Services\Testing\ClearTestDataService;

class ClearTestDataController extends BaseController
{
    private ClearTestDataService $testService;

    public function __construct()
    {
        $this->testService = new ClearTestDataService();
    }

    public function clearMemberTestData()
    {
        $json = $this->request->getJSON(true);
        $data = [
            'phone' => $json['phone'],
            'email' => $json['email']
        ];

        $result = $this->testService->clearMemberTestData($data);

        return $this->response->setJSON([
            'message' => $result ? 
                        'Successfully delete member test data' : 
                        'Failed or no member data to be deleted'
        ]);
    }

    public function clearCasualTestData()
    {
        $json = $this->request->getJSON(true);
        $data = [
            'phone' => $json['phone'],
            'email' => $json['email']
        ];

        $result = $this->testService->clearCasualTestData($data);

        return $this->response->setJSON([
            'message' => $result ? 
                        'Successfully delete casual test data' : 
                        'Failed or no casual pass data to be deleted'
        ]);

    }
}