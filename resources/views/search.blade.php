<!DOCTYPE html>
<html>

<head>
    <title>Amazon Search</title>
</head>

<body>
    <form action="/search" method="GET">
        <label for="price_min">Price Min:</label>
        <input type="number" name="price_min" required>
        <label for="price_max">Price Max:</label>
        <input type="number" name="price_max" required>
        <label for="review_min">Review Min:</label>
        <input type="number" name="review_min" required>
        <label for="review_max">Review Max:</label>
        <input type="number" name="review_max" required>
        <button type="submit">Search</button>
    </form>

    <div id="results">
        @if(isset($links))
            @foreach($links as $link)
                <p>{{ $link }}</p>
            @endforeach
        @endif
    </div>
</body>

</html>