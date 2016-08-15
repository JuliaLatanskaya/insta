<?php
spl_autoload_register(
    function($class)
    {
        static $classes = null;
        
        if ($classes === null) {
            $classes = array(
                'insta\\models\\post' => '/Post.php',
                'insta\\models\\postsdata' => '/PostsData.php',
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    },
    true,
    false
);
