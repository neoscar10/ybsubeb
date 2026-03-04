$layoutPath = "resources/views/layouts/admin.blade.php"
$c = Get-Content $layoutPath
$newLayout = $c[0..1905] + "@yield('content')" + $c[2462..($c.Count-1)]
$newLayout | Set-Content $layoutPath

$dashPath = "resources/views/admin/dashboard.blade.php"
(Get-Content $dashPath) -replace 'assets/', '{{ asset(''themes/velzon'') }}/' | Set-Content $dashPath
