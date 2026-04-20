<?php 
use Scriptotek\GoogleBooks\GoogleBooks;
return [
      'page.changeStatus:after' => function ($newPage,$oldPage) {
          if($newPage->template()->name() == 'roast') {
            if (!$oldPage->isListed()) {
                $newPage->changeSort(0);
            }
          }
      },
      'page.create:after' => function($page) {
          if($page->intendedTemplate()->name() == 'roaster') {
              $url = $page->website(); 
              $filename = $page->slug() . "-logo.jpg";

              // Download the og image of the site to a tmp file 
              $tmpFile = fetch_og_image($url, $filename);
                
              if($tmpFile) {
                // Move the tmp file to the current page 
                $file = $page->createFile([
                    'source'   => $tmpFile,
                    'filename' => $filename,
                ], true);
                
                // Update the field on the page
                $page->update([
                    'logo' => Data::encode([$file->filename()], 'yaml')
                ]);
            }
        }

          if($page->intendedTemplate()->name() == 'book') {
            $tmpPath = kirby()->root() . '/tmp/';
            $filename = $page->slug() . "-cover.jpg";
            ini_set("allow_url_fopen", 1);

            $coverUrl = '';
            $author = $page->author();

            $title = str_replace(' ', '+', $page->title());
            $providedAuthor = str_replace(' ', '+', $page->author());
            
            $gBooks = new GoogleBooks([
                'key' => env('GOOGLE_BOOKS_API_KEY'),
                'maxResults' => 10,
            ]);

            $query = "intitle:{$title}";
            if($providedAuthor) {
                $query .= "+inauthor:{$providedAuthor}";
            }

            $result = $gBooks->volumes->firstOrNull($query);

            if($result) {
                $coverUrl = $result->getCover();
                if(!$providedAuthor) $author = join(', ', $result->authors);
            }
    
            if($coverUrl) {
                $page->update(['debug' => $page->debug() . ' * ' . $coverUrl]);
               $response = Remote::get($coverUrl);
                if ($response->code() === 200) {
                    // Write the file to a temp path
                    F::write( $tmpPath . $filename, $response->content());
                    $tmpFile = $tmpPath.$filename;
                }
            }

            if(isset($tmpFile)) {
             // Move the tmp file to the current page 
                $file = $page->createFile([
                    'source'   => $tmpFile,
                    'filename' => $filename,
                ], true);
                // Update the field on the page
                $page->update([
                    'cover' => Data::encode([$file->filename()], 'yaml'),
                    'author' => $author,
                ]);

            }
        }
    }
];
