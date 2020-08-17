<!DOCTYPE html>
<html lang="en">

<head>
  <title>Print Shop Cost Estimator</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
  <link rel="manifest" href="site.webmanifest">
</head>

<body>
  <?php
  function getPrice($styleCode) {
  $curl = curl_init();
  $startURL = 'https://www.alphashirt.com/cgi-bin/online/xml/inv-request.w?sr=';
  $endURL = '&cc=00&sc=4&pr=y&zp=30044&userName=abcdefgh&password=abcdefgh';
  $together = $startURL . $styleCode . $endURL;
  curl_setopt_array($curl, array(
    CURLOPT_URL => $together,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "",
    CURLOPT_HTTPHEADER => array(
      "Postman-Token: 4e7535d8-2a88-4dc8-923f-a71ffbc679ec",
      "cache-control: no-cache"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    // echo $response;
  }

  $xml = simplexml_load_string($response);
  if ($xml === false) {
    echo "Failed loading XML: ";
    foreach(libxml_get_errors() as $error) {
      echo "<br>", $error->message;
    }
  } else {
    // PRICE IS HERE
    // echo $xml->item[0]['price'];
    $rawPrice = $xml->item[0]['price'];
    // echo $rawPrice;
    $rawPrice2 = str_replace("$", "", $xml->item[0]['price']);
    // echo $rawPrice2;

    $finalPrice = (2 * (float)$rawPrice2);
    return $finalPrice;
    // echo "<br>" . $finalPrice;

  }

  }
   ?>

  <div class="jumbotron text-center">
    <h1>Lunic Cost Estimator</h1>
    <p>Type in the amount of colors, number of shirts, and shirt style then hit button!</p>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-sm-4">
        <h3>How many colors?</h3>
        <p>Each color calls for a screen, each screen is $15</p>
        <p>If design is on a dark color shirt, a white base coat will be added</p>
      </div>
      <div class="col-sm-4">
        <h3>How many shirts?</h3>
        <p>The more shirts that are printed, the lower the cost of each shirt</p>
        <p>It is more cost effective to print more shirts</p>
      </div>
      <div class="col-sm-4">
        <h3>What type of shirt?</h3>
        <p>These are our popular shirt styles:</p>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Style</th>
              <th>Avg. Price</th>

            </tr>
          </thead>
          <!-- We should only make 1 api call, we are slowing everything down by making so many requests -->

          <tbody>
            <tr>
              <td>G500</td>
              <td><?php echo getPrice("g500"); ?></td>

            </tr>
            <tr>
              <td>G800</td>
              <td><?php echo getPrice("g800"); ?></td>

            </tr>

          </tbody>
        </table>
      </div>
    </div>

    <div class='row'>
      <div class='col-lg-12'>
        <p>
          <br>
        </p>
      </div>
    </div>
    <!-- <h2>Add your places!</h2> -->
    <div class='row'>
      <div class='col-sm-4'>
        <form action="second.php" method="get">
          <div class="form-group">
            <label for="clr">Number of Colors:</label>
            <input type="text" class="form-control" id="usr" name="numColors" autofocus pattern="[1-6]" title="Number from 1-6" placeholder="1-6" required>
          </div>


      </div>
      <div class='col-sm-4'>
        <form action="second.php">
          <div class="form-group">
            <label for="usr">Number of Shirts:</label>
            <input type="text" class="form-control" id="usr" name="numShirts" pattern="^([1-9][0-9]{0,2}|1000)$" title="Number from 1-12" placeholder="1-1000" required>
          </div>


      </div>
      <div class='col-sm-4'>
        <form action="second.php">
          <div class="form-group">
            <label for="usr">Style of Shirt:</label>
            <input type="text" class="form-control" id="usr" name="style" required>
          </div>


      </div>

    </div>
    <div class='row'>
      <div class='col-lg-12'>
        <button type="submit" class="btn btn-primary btn-block">Calculate Price!</button>
      </div>
      </form>
    </div>
    <div class='row'>
      <div class='col-lg-12'>
        <p>
          <br>
          To price check enter 1 color, 999 shirts, and then the style code.
          <br>
        </p>
      </div>
    </div>
  </div>



</body>

</html>
