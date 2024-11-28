<?php
require_once('../../../vendor/Config.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $db = getDB();
        $db->beginTransaction();

        // 1. Cek dan dapatkan data user
        $check_query = "SELECT nip, status FROM tbl_user WHERE id = :id FOR UPDATE";
        $stmt = executeQuery($check_query, [':id' => $id]);
        $user = $stmt->fetch();
        
        if (!$user) {
            throw new Exception("User tidak ditemukan.");
        }

        // 2. Update status menjadi INACTIVE sebelum penghapusan
        $update_status = "UPDATE tbl_user SET status = 'INACTIVE' WHERE id = :id";
        executeQuery($update_status, [':id' => $id]);

        // 3. Hapus data terkait
        $delete_user = "DELETE FROM tbl_user WHERE id = :id";
        executeQuery($delete_user, [':id' => $id]);

        // 4. Reorder ID setelah penghapusan
        $reorder_query = "
            UPDATE tbl_user t1
            SET t1.id = (
                SELECT new_id 
                FROM (
                    SELECT id, ROW_NUMBER() OVER (ORDER BY id) as new_id 
                    FROM tbl_user
                ) t2 
                WHERE t2.id = t1.id
            )";
        executeQuery($reorder_query);

        // 5. Reset sequence berdasarkan ID terakhir
        $get_max_id = "SELECT NVL(MAX(id), 0) as max_id FROM tbl_user";
        $stmt = executeQuery($get_max_id);
        $max_id = $stmt->fetch()['MAX_ID'];
        $next_id = $max_id + 1;

        // Drop dan buat ulang sequence
        try {
            executeQuery("DROP SEQUENCE seq_id_user");
            executeQuery("CREATE SEQUENCE seq_id_user START WITH {$next_id} INCREMENT BY 1 NOCACHE NOCYCLE");
        } catch (Exception $e) {
            error_log("Sequence reset error: " . $e->getMessage());
        }

        // 6. Commit transaction
        $db->commit();

        // 7. Clear output buffer
        while (ob_get_level()) {
            ob_end_clean();
        }

        // 8. Close connection
        $db = null;

        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Location: user.php?status=success&message=" . urlencode("User berhasil dihapus") . "&t=" . time());
        exit();

    } catch (Exception $e) {
        if (isset($db) && $db->inTransaction()) {
            $db->rollBack();
        }

        error_log("Delete User Error: " . $e->getMessage());
        
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        header("Location: user.php?status=error&message=" . urlencode($e->getMessage()) . "&t=" . time());
        exit();
    }
} else {
    header("Location: user.php?status=error&message=" . urlencode("ID tidak valid"));
    exit();
}
?>