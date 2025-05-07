<?php
class SongReup {
	public static function reup($result) {
		include dirname(__FILE__)."/connection.php";
		$resultarray = explode('~|~', $result);
		$uploadDate = time();
		$query = $db->prepare("INSERT INTO songs (name, authorID, authorName, size, download)
		VALUES (:name, :authorID, :authorName, :size, :download)");
		$query->execute([':name' => $resultarray[3], ':authorID' => $resultarray[5], ':authorName' => $resultarray[7], ':size' => $resultarray[9], ':download' => $resultarray[13]]);
		file_put_contents('test.txt', json_encode($resultarray));
        return $db->lastInsertId();
	}
//    public static function reup($result) {
//		include dirname(__FILE__)."/connection.php";
//		$resultarray = explode('~|~', $result);
//		$uploadDate = time();
//		$query = $db->prepare("INSERT INTO songs (ID, name, authorID, authorName, size, download)
//		VALUES (:id, :name, :authorID, :authorName, :size, :download)");
//		$query->execute([':id'=>$resultarray[1], ':name' => $resultarray[3], ':authorID' => $resultarray[5], ':authorName' => $resultarray[7], ':size' => $resultarray[9], ':download' => $resultarray[13]]);
//		return $db->lastInsertId();
//	}
}
?>