<?php 

function getShopData($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;

    try{
        $sql = 'SELECT e.last_claim,
                    e.isVip,
                    e.isClaimed,
                    p.cash,
                    p.chip
                FROM profiles p
                INNER JOIN economy e ON p.user_id = e.user_id
                WHERE p.user_id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if($data){
            $canDailyClaim = true;
            if($data['last_claim'] !== null){
                $lastClaim = new DateTime($data['last_claim']);
                $today = new DateTime('today');
                if($lastClaim->format('Y-m-d') === $today->format('Y-m-d')){
                    $canDailyClaim = false;
                }
            }

            echo json_encode([
                'success' => true,
                'cash' => (int)$data['cash'],
                'chip' => (int)$data['chip'],
                'isVip' => (bool)$data['isVip'],
                'isClaimed' => (bool)$data['isClaimed'],
                'canClaimDaily' => $canDailyClaim
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal ambil data']);
        }
    } catch (Exception $e){
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]));
    }
}

function claimDaily($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;

    try{
        //Cek Vip dan udah claim belum
        $stmt = $conn->prepare('SELECT isVip, last_claim FROM economy WHERE user_id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$data){
            echo json_encode(['success' => false, 'message' => 'User tidak ditemukan']);
            exit;
        }

        $chipAmount = 100;
        $today = new DateTime('today');
        if($data['last_claim'] !== null){
            $lastClaim = new DateTime($data['last_claim']);
            if($lastClaim->format('Y-m-d') === $today->format('Y-m-d')){
                echo json_encode(['success' => false, 'message' => 'Hari ini sudah pernah claim']);
                exit;
            }
        }

        if($data['isVip']){
            $chipAmount = 500;
        }

        $conn->beginTransaction();
        
        $sql = 'UPDATE profiles p
                JOIN economy e ON e.user_id = p.user_id
                SET p.chip = p.chip + ?, e.last_claim = ?
                WHERE p.user_id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$chipAmount, $today->format('Y-m-d'), $id]);

        $conn->commit();

        echo json_encode(['success' => true, 'message' => 'Berhasil claim', 'chip' => $chipAmount,'vip' => (bool)$data['isVip']]);
    } catch(Exception $e){
        if ($conn->inTransaction()) {
            $conn->rollback();
        }
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]));
    }
}

function claimWelcomeBonus($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;
    try{
        $conn->beginTransaction();

        $sql = 'UPDATE profiles p
                JOIN economy e ON e.user_id = p.user_id
                SET p.chip = p.chip + 1000, e.isClaimed = 1
                WHERE p.user_id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        $conn->commit();

        echo json_encode(['success' => true, 'message' => 'Berhasil claim']);
    } catch(Exception $e){
         if ($conn->inTransaction()) {
            $conn->rollback();
        }
        die(json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]));
    }
}

function buyVip($conn, $jwt_token){
    $id = auth($jwt_token)->user_id;

    try {
        $sql = 'SELECT p.cash, e.isVip, p.badges
                FROM profiles p
                INNER JOIN economy e ON e.user_id = p.user_id
                WHERE p.user_id = ?';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'User tidak ditemukan']);
            exit;
        }

        if ($data['isVip']) {
            echo json_encode(['success' => false, 'message' => 'Anda sudah menjadi member VIP']);
            exit;
        }

        if ((int)$data['cash'] < 50000) {
            echo json_encode(['success' => false, 'message' => 'Cash Anda tidak mencukupi']);
            exit;
        }

        $conn->beginTransaction();

        // Update cash + isVip
        $sqlUpdate = 'UPDATE profiles p
                      JOIN economy e ON e.user_id = p.user_id
                      SET p.cash = p.cash - 50000, e.isVip = 1
                      WHERE p.user_id = ?';
        $stmt = $conn->prepare($sqlUpdate);
        $stmt->execute([$id]);

        // Decode badges JSON (default kalau kosong)
        $badges = $data['badges'] ? json_decode($data['badges'], true) : ["used" => [], "unused" => []];

        // Tambahin badge VIP kalau belum ada
        if (!in_array("VIP", $badges["used"]) && !in_array("VIP", $badges["unused"])) {
            $badges["unused"][] = "VIP";
        }

        $newJson = json_encode($badges);
        $stmt = $conn->prepare("UPDATE profiles SET badges = ? WHERE user_id = ?");
        $stmt->execute([$newJson, $id]);

        $conn->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Berhasil jadi VIP + dapat badge VIP',
            'badges'  => $badges   // kirim JSON array terbaru
        ]);
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]);
    }
}

function exchangeCashToChip($conn, $jwt_token){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = auth($jwt_token)->user_id;
    $cashAmount = (int)$data['cashAmount'];
    $chipAmount = (int)$data['chipAmount'];
    $needVip = (bool)$data['needVip'];

    if($needVip){
        $stmt = $conn->prepare('SELECT isVip FROM economy WHERE user_id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!(bool)$data['isVip']){
            echo json_encode(['success' => false, 'message' => 'Hanya untuk member VIP!']);
            exit;
        }
    }

    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare('UPDATE profiles SET cash = cash - ?, chip = chip + ? WHERE user_id = ? AND cash >= ?');
        $stmt->execute([$cashAmount, $chipAmount, $id, $cashAmount]);
        
        $conn->commit();

        echo json_encode(['success' => true, 'message' => 'Berhasil tukar']);
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]);
    }
}
function exchangeChipToCash($conn, $jwt_token){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = auth($jwt_token)->user_id;
    $cashAmount = (int)$data['cashAmount'];
    $chipAmount = (int)$data['chipAmount'];
    $needVip = (bool)$data['needVip'];

    if($needVip){
        $stmt = $conn->prepare('SELECT isVip FROM economy WHERE user_id = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!(bool)$data['isVip']){
            echo json_encode(['success' => false, 'message' => 'Hanya untuk member VIP!']);
            exit;
        }
    }

    try{
        $conn->beginTransaction();

        $stmt = $conn->prepare('UPDATE profiles SET cash = cash + ?, chip = chip - ? WHERE user_id = ? AND chip >= ?');
        $stmt->execute([$cashAmount, $chipAmount, $id, $chipAmount]);
        
        $conn->commit();

        echo json_encode(['success' => true, 'message' => 'Berhasil tukar']);
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan di server',
            'error'   => $e->getMessage()
        ]);
    }
}

function exchangeCustom($conn, $jwt_token){
    $data = json_decode(file_get_contents("php://input"), true);
    $id = auth($jwt_token)->user_id;
    $direction = $data['direction'];
    $amount = (int)$data['amount']; 

    // Cek VIP
    $stmt = $conn->prepare('SELECT isVip FROM economy WHERE user_id = ?');
    $stmt->execute([$id]);
    $isVip = (bool)$stmt->fetch(PDO::FETCH_ASSOC)['isVip'];

    try {
        $conn->beginTransaction();

        if($direction === "custom-chip-to-cash"){
            $rate = $isVip ? 4.5 : 4;
            $cash = $amount * $rate;

            $stmt = $conn->prepare('UPDATE profiles 
                SET cash = cash + ?, chip = chip - ? 
                WHERE user_id = ? AND chip >= ?');
            $stmt->execute([$cash, $amount, $id, $amount]);

        } elseif($direction === "custom-cash-to-chip"){
            $rate = $isVip ? 4.5 : 5;
            $cash = $amount * $rate;

            $stmt = $conn->prepare('UPDATE profiles 
                SET chip = chip + ?, cash = cash - ? 
                WHERE user_id = ? AND cash >= ?');
            $stmt->execute([$amount, $cash, $id, $cash]);

        } else {
            throw new Exception("Arah konversi tidak valid!");
        }
        
        if ($stmt->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Saldo tidak mencukupi']);
            exit;
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Tukar berhasil', 'cash' => $cash]);

    } catch (Exception $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
