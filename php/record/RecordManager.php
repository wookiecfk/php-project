<?php

require_once('../database/DatabaseConnectionWrapper.php');
require_once ('../helper/helper.php');
class RecordManager
{
    public function createRecord($fileName, $title, $IRSCCode, $compositor, $author, $coverAuthor, $durationInSeconds)
    {
        try {
            $con = $this->getDatabaseConnection();
            /*TODO Should have server side validations for data length and integrity - for now safeguard against injection
            and let db throw out an exception on bounds violation*/
            $stmt = $con->prepare(
                "INSERT INTO record (file_name, title, IRSC_code, compositor, author, cover_author, duration_seconds) 
                    values (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssssi', $fileName, $title, $IRSCCode, $compositor, $author, $coverAuthor,
                $durationInSeconds);
            return $stmt->execute();
        } finally {
            if (isset($con)) {
                $con->close();
            }
        }
    }

    public function modifyRecord($fileName, $title, $IRSCCode, $compositor, $author, $coverAuthor, $durationInSeconds,
                                 $id) {
        try {
            $con = $this->getDatabaseConnection();
            $stmt = $con->prepare("UPDATE record SET
            file_name = ?,
            title = ?,
            IRSC_code = ?, 
            compositor = ?, 
            author = ?, 
            cover_author = ?, 
            duration_seconds = ?
            where record_id = ?");

            $stmt->bind_param("ssssssii", $fileName, $title, $IRSCCode, $compositor, $author, $coverAuthor,
                $durationInSeconds, $id);
            return $stmt->execute();
        } finally {
            if(isset($con)) {
                $con->close();
            }
        }
    }

    public function deleteRecord($id) {
        try {
            $con = $this->getDatabaseConnection();
            $stmt = $con->prepare("DELETE FROM record where id = ?");
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } finally {
            if(isset($con)) {
                $con->close();
            }
        }
    }

    private function getDatabaseConnection() {
        $con_wrapper = new DatabaseConnectionWrapper();
        return $con_wrapper->getDatabaseConnection();
    }

} ?>