<!DOCTYPE html>
<html>
<head>
    @vite(['resources/js/app.js'])
</head>
<body>

<h1>evento</h1>

<script>
Echo.channel('produtos')
    .listen('NovoProdutoAdicionado', (e) => {
        console.log(e);
    });
</script>

</body>
</html>
