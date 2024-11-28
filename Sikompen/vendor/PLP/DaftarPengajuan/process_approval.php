<?php
require_once(__DIR__ . '/../../../vendor/Config.php');

// Check session
session_start();
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['role']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../../../login.php');
    exit();
}

try {
    $pdo = getDB();
    
    // Proses single approval
    if (isset($_POST['pengajuan_id'])) {
        $id = $_POST['pengajuan_id'];
        
        $stmt = $pdo->prepare("UPDATE tbl_pengajuan 
                             SET status_approval1 = 'BERHASIL', 
                                 updated_at = CURRENT_TIMESTAMP 
                             WHERE id_pengajuan = :id 
                             AND status_approval1 <> 'BERHASIL'");
        
        $stmt->execute(['id' => $id]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['success_message'] = "Pengajuan berhasil disetujui.";
        } else {
            $_SESSION['error_message'] = "Tidak dapat menyetujui pengajuan.";
        }
    }
    // Proses multiple approval
    elseif (isset($_POST['selected_ids']) && !empty($_POST['selected_ids'])) {
        // Begin transaction
        $pdo->beginTransaction();
        
        try {
            $success_count = 0;
            $selected_ids = $_POST['selected_ids'];
            
            // Prepare statement
            $stmt = $pdo->prepare("UPDATE tbl_pengajuan 
                                 SET status_approval1 = 'BERHASIL', 
                                     updated_at = CURRENT_TIMESTAMP 
                                 WHERE id_pengajuan = :id 
                                 AND status_approval1 <> 'BERHASIL'");
            
            // Process each ID
            foreach ($selected_ids as $id) {
                $stmt->bindValue(':id', $id);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $success_count++;
                }
            }
            
            // Commit transaction
            $pdo->commit();
            
            if ($success_count > 0) {
                $_SESSION['success_message'] = "Berhasil menyetujui $success_count pengajuan.";
            } else {
                $_SESSION['error_message'] = "Tidak ada pengajuan yang dapat disetujui.";
            }
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $pdo->rollBack();
            throw $e;
        }
    } else {
        $_SESSION['error_message'] = "Silahkan pilih pengajuan yang akan disetujui.";
    }
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Terjadi kesalahan: " . $e->getMessage();
}

// Redirect back to the main page
header('Location: DaftarPengajuanPLP.php');
exit();
?>