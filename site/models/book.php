<?php
use Kirby\Uuid\Uuid;

class BookPage extends Page {
    public function sortIndex() {
        if($this->completion_date() == '') {
            return time();
        } else {
            return $this->completion_date()->toDate();
        }
    }

    public function children(): Pages {
        $highlights = [];
        $file = $this->root() . '/highlights.json';

        if (!file_exists($file)) {
            return Pages::factory($highlights, $this);
        }

        $items = json_decode(file_get_contents($file), true) ?? [];

        foreach ($items as $i => $text) {
            $highlights[] = [
                'slug'     => 'highlight-' . ($i + 1),
                'num'      => $i + 1,
                'template' => 'highlight',
                'model'    => 'highlight',
                'content'  => [
                    'text' => $text,
                    'uuid'     => Uuid::generate(),
                ],
            ];
        }

        return $this->children = Pages::factory($highlights, $this);
    }
}
