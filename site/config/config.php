<?php
require_once('vendor/autoload.php');
require_once(__DIR__ . '/../utils/fetch_og_image.php');
use Scriptotek\GoogleBooks\GoogleBooks;

return [
    'debug'  => true,
    'url' => '/',
  's1syphos.highlight' => [
    'class' => 'hljs',
    'languages' => ['html', 'js', 'css', 'ruby', 'erb']
  ],
  'hooks' => [
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
                'key' => 'AIzaSyAc65eroI7eKyfX6gIH0Lr29B2YqVq7hTQ',
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

            //// OPEN LiBRARY 
            // $searchApiUrl = 'https://openlibrary.org/search.json?title=%s';
            // $coverApiUrl = 'https://covers.openlibrary.org/b/id/%s-%s.jpg';
            // $size = 'L';
            // 
            // $apiQuery = sprintf($searchApiUrl, $title);
            // 
            // $page->update(['debug' => $page->debug() . $apiQuery]);
            // 
            // $json = file_get_contents($apiQuery);
            // $result = json_decode($json);
            // $coverUrl = '';
            //
            // if($result) {
            //     $page->update(['debug' => json_encode($result)]);
            //     if(count($result->docs)) {
            //         $page->update(['debug' => $page->debug() . ' * there are some docs']);
            //         if(property_exists($result->docs[0], 'cover_i')) {
            //             $page->update(['debug' => $page->debug() . ' * there is a cover_i']);
            //             $id = $work->cover_i;
            //             $coverUrl = sprintf($coverApiUrl, $id, $size);
            //         }
            //     }
            // }
    
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
  ]
];
