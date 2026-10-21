<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\DTO\Payment\SubmitTransferProofDto;
use App\Services\Payment\TransferPaymentService;

class TransferPaymentController extends BaseController
{
    private TransferPaymentService $transferPaymentService;

    public function __construct()
    {
        $this->transferPaymentService = new TransferPaymentService();
    }

    public function submitProof()
    {
        $rules = [
            'transaction_id' => 'required|integer',
            'sender_bank'    => 'required|max_length[100]',
            'sender_name'    => 'required|max_length[150]',
            'proof_image'    => [
                'rules' => 'uploaded[proof_image]|is_image[proof_image]|mime_in[proof_image,image/jpg,image/jpeg,image/png,image/webp]|max_size[proof_image,2048]',
                'errors' => [
                    'uploaded' => 'Transfer proof image is required.',
                    'is_image' => 'Transfer proof must be an image.',
                    'mime_in'  => 'Transfer proof must be JPG, JPEG, PNG, or WEBP.',
                    'max_size' => 'Transfer proof image must not exceed 2MB.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('proof_image');

        if (!$file || !$file->isValid()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Invalid transfer proof image.');
        }

        $uploadDir = FCPATH . 'uploads/transfer-proofs';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $newFileName = $file->getRandomName();

        $file->move($uploadDir, $newFileName);

        $proofImagePath = 'uploads/transfer-proofs/' . $newFileName;

        $dto = SubmitTransferProofDto::fromPost(
            $this->request->getPost(),
            $proofImagePath
        );

        $result = $this->transferPaymentService->submitProof(
            transactionId: $dto->transactionId,
            senderBank: $dto->senderBank,
            senderName: $dto->senderName,
            proofImagePath: $dto->proofImagePath,
        );

        if (!$result['ok']) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $result['error']);
        }

        return redirect()
            ->back()
            ->with('success', 'Transfer proof uploaded successfully. Please wait for admin confirmation.');
    }
}