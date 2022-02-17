<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Memory CSS -->
    <link href="/assets/css/memory.css" rel="stylesheet" crossorigin="anonymous">
    <link href="/assets/css/sprites.css" rel="stylesheet" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <title>Memory</title>

</head>

<body class="text-center">

    <main class="form-signin">

        <h1 class="h3 mb-3 fw-normal">Memory</h1>

        <div id="board" class="mb-3">

            <table class="table-bordered border-primary">
                <thead></thead>
                <tbody></tbody>
            </table>

        </div>

        <div class="w-100"></div>


        <div class="mb-3">
            <button id="start-btn" class="w-100 btn btn-lg btn-primary" type="button">Start</button>
        </div>

        <div class="w-100"></div>


        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <div class="w-100"></div>

        <div id="list" class="mb-3">
            <table class="table table-bordered border-primary caption-top">
                <caption>Game results</caption>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Success</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="no-data">
                        <td colspan="4">No results</td>
                    </tr>
                </tbody>
            </table>
        </div>


    </main>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Memory Js -->
    <script src="/assets/js/memory.js" crossorigin="anonymous"></script>

</body>

</html>