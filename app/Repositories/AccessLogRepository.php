<?php

namespace App\Repositories;

use App\Contracts\Repositories\AccessLogRepositoryInterface;
use App\Traits\RepositoryResponseTrait;
use CodeIgniter\Database\Exceptions\DatabaseException;

class AccessLogRepository implements AccessLogRepositoryInterface
{
    use RepositoryResponseTrait;
    private $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function findAccessHistory(array $filter, int $limit, int $offset): array
    {
        try {
            $params = [];
            $where = [];

            $baseSql = "
                FROM (
                    SELECT 
                    'MEMBER_BRANCH' AS source_branch,
                        al.id AS access_log_id,
                        al.`type` AS access_type,
                        m.name AS name,
                        m.phone AS phone,
                        m.email AS email,
                        m.member_type AS member_type,
                        al.access_token AS access_token,
                        al.check_in_at AS check_in_at,
                        al.check_out_at AS check_out_at
                    FROM access_logs al
                    JOIN member_access ma 
                        ON al.access_token = ma.access_token
                    JOIN memberships ms 
                        ON ma.membership_id = ms.id
                    JOIN members m 
                        ON ms.member_id = m.id
                    WHERE al.`type` = 'MEMBER'

                    UNION ALL

                    SELECT
                        'CASUAL_BRANCH' AS source_branch,
                        al.id AS access_log_id,
                        al.`type` AS access_type,
                        mr.name AS name,
                        mr.phone AS phone,
                        mr.email AS email,
                        mr.target_member_type AS member_type,
                        al.access_token AS access_token,
                        al.check_in_at AS check_in_at,
                        al.check_out_at AS check_out_at
                    FROM access_logs al
                    JOIN casual_passes cp 
                        ON al.access_token = cp.access_token
                    JOIN membership_requests mr 
                        ON cp.membership_request_id = mr.id
                    WHERE al.`type` = 'CASUAL'
                ) AS history
                WHERE 1 = 1
            ";

            if (!empty($filter['search'])) {
                $where[] = "AND (history.name LIKE ? OR history.phone LIKE ?)";
                $params[] = '%' . $filter['search'] . '%';
                $params[] = '%' . $filter['search'] . '%';
            }

            if (!empty($filter['type']) && strtolower($filter['type']) !== 'all') {
                $where[] = "AND history.access_type = ?";
                $params[] = strtoupper($filter['type']);
            }

            if (!empty($filter['from'])) {
                $where[] = "AND history.check_in_at >= ?";
                $params[] = $filter['from'] . ' 00:00:00';
            }

            if (!empty($filter['to'])) {
                $where[] = "AND history.check_in_at <= ?";
                $params[] = $filter['to'] . ' 23:59:59';
            }

            $whereSql = "\n" . implode("\n", $where);

            $dataSql = "
                SELECT *
                {$baseSql}
                {$whereSql}
                ORDER BY history.check_in_at DESC
                LIMIT ? OFFSET ?
            ";

            $dataParams = array_merge($params, [$limit, $offset]);

            $data = $this->db
                ->query($dataSql, $dataParams)
                ->getResultArray();

            $countSql = "
                SELECT COUNT(*) AS total
                {$baseSql}
                {$whereSql}
            ";

            $count = $this->db
                ->query($countSql, $params)
                ->getRowArray();

            return $this->successResponse([
                'rows' => $data,
                'total' => (int) ($count['total'] ?? 0),
            ]);
        } catch(DatabaseException $de){
            return $this->failFromThrowable($de);

        } catch (\Throwable $th) {
            return $this->failFromThrowable($th);
        }
    }
    
}