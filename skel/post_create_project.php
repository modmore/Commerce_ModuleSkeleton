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

$dir_iterator = new RecursiveDirectoryIterator(dirname(__DIR__) . '/core');
$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
/** @var SplFileInfo $file */
foreach ($iterator as $file) {
    if ($file->isFile()) {
        applyValues($file, $replaces);
    }
}
$dir_iterator = new RecursiveDirectoryIterator(dirname(__DIR__) . '/_build');
$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
/** @var SplFileInfo $file */
foreach ($iterator as $file) {
    if ($file->isFile()) {
        applyValues($file, $replaces);
    }
}
$dir_iterator = new RecursiveDirectoryIterator(dirname(__DIR__) . '/_bootstrap');
$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
/** @var SplFileInfo $file */
foreach ($iterator as $file) {
    if ($file->isFile()) {
        applyValues($file, $replaces);
    }
}

$root = rtrim(dirname(__DIR__), '/') . '/';
rename($root . 'core/components/commerce_projectname/src/Modules/Projectname.php',
    $root . 'core/components/commerce_projectname/src/Modules/' . $casedProjectname . '.php');
rename($root . 'core/components/commerce_projectname/model/schema/commerce_projectname.mysql.schema.xml',
    $root . 'core/components/commerce_projectname/model/schema/commerce_' . $projectname . '.mysql.schema.xml');
rename($root . 'core/components/commerce_projectname',
    $root . 'core/components/commerce_' . $projectname);

echo "Removing dist files\n";

// Then we drop the skel dir, as it contains skeleton stuff.
delTree('skel');
delTree('vendor');
unlink('composer.json');
unlink('composer.lock');
unlink('readme.md');

echo "Refreshing autoloader\n";

exec('cd ' . $root . 'core/components/commerce_' . $projectname . '/ && composer dump-autoload', $out);
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
