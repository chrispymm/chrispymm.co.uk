<?php


class BookPage extends Page {
    public function sortIndex() {
        if($this->completion_date() == '') {
            return time();
        } else {
            return $this->completion_date()->toDate();
        }
    }
}
