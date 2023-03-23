<?php

// We get the project name from the name of the path that Composer created for us.
$projectname = basename(realpath("."));

$casedProjectname = implode('', array_map('ucfirst', explode('-', $projectname)));
$projectname = strtolower($projectname);

echo "Project name $casedProjectname taken from directory name\n";

$replaces = [
    'projectname' => $projectname,
    'Projectname' => $casedProjectname,
];

$root = rtrim(dirname(__DIR__), '/') . '/';
$replacePaths = [
    $root . 'core',
    $root . '_build',
    $root . '_bootstrap',
    $root . 'skel/composer.json',
    $root . 'skel/readme.md',
    $root . '.gitignore',
];

foreach ($replacePaths as $path) {
    if (is_dir($path)) {
        $dir_iterator = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                applyValues($file, $replaces);
            }
        }
    }
    elseif (is_file($path)) {
        applyValues($path, $replaces);
    }
}

unlink('composer.json');
unlink('composer.lock');
unlink('readme.md');

rename($root . 'core/components/commerce_projectname/model/schema/commerce_projectname.mysql.schema.xml',
    $root . 'core/components/commerce_projectname/model/schema/commerce_' . $projectname . '.mysql.schema.xml');
rename($root . 'core/components/commerce_projectname',
    $root . 'core/components/commerce_' . $projectname);
rename($root . 'skel/composer.json', $root . 'composer.json');
rename($root . 'skel/readme.md', $root . 'readme.md');
copy($root . 'config.core.sample.php', $root . 'config.core.php');

echo "Removing dist files\n";

// Then we drop the skel dir, as it contains skeleton stuff.
delTree('skel');
delTree('vendor');

echo "Refreshing autoloader\n";

exec('cd ' . $root . 'core/components/commerce_' . $projectname . '/ && composer dump-autoload', $out);
foreach ($out as $line) {
    echo $line . "\n";
}

exec('composer update', $out);
foreach ($out as $line) {
    echo $line . "\n";
}

echo "Done!\n";

/**
 * A method that will read a file, run a strtr to replace placeholders with
 * values from our replace array and write it back to the file.
 *
 * @param string $target the filename of the target
 * @param array $replaces the replaces to be applied to this target
 */
function applyValues($target, $replaces)
{
    file_put_contents(
        $target,
        strtr(
            file_get_contents($target),
            $replaces
        )
    );
}


/**
 * A simple recursive delTree method
 *
 * @param string $dir
 * @return bool
 */
function delTree($dir)
{
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

exit(0);
