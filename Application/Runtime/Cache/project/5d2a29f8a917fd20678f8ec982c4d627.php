<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<div>
    <form action="childDataOrder" method="post">
    <table>
        <tr><td>childID</td><td><input name="childID"></td></tr>
        <tr><td>childBirthdate</td><td><input name="childBirthdate"></td></tr>
        <tr><td>latestDate</td><td><input name="latestDate"></td></tr>
        <tr><td><input type="submit" value="排序"></td></tr>
    </table>
    </form>
</div>
</body>
</html>