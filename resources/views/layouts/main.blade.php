<!-- views/layouts/main.blade.php -->

<!DOCTYPE html>
<html>
	<body>
		@include('partials._nav')
		<div class="container">
			@yield('content')
		</div>
	</body>
</html>
