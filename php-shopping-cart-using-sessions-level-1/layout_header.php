<?php
$_SESSION['cart']=isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <style>
    /* Remove the navbar's default margin-bottom and rounded borders */ 
    .navbar {
      /*margin-bottom: 50;*/
      border-radius: 0;
    }

    
    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    
        /* Remove the jumbotron's default bottom margin */ 
     .jumbotron {
     	margin-bottom: 0px;
		/*background-image: url("/img/milpaBanner.jpg");*/
		background-color: #17234E;
		background-size: cover;
		color: white;
    }

  </style>
    <!-- custom CSS -->
    <link rel="stylesheet" href="libs/css/custom.css" />
</head>
<body>

<?php //include 'navigation.php'; ?>

<div class="jumbotron">
  <div class="container text-center">
    <h1>Online Store</h1>      
    <p>Buy stuff and spend your money on us</p>
  </div>
</div>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>

      <a class="navbar-brand" href="#">Logo</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#">Products</a></li>
        <li><a href="#">Deals</a></li>
        <li><a href="#">Stores</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-user"></span> Your Account</a></li>
          <li <?php echo $page_title=="Cart" ? "class='active'" : ""; ?> >
              <a href="cart.php">
                  <?php
                  // count products in cart
                  $cart_count=count($_SESSION['cart']);
                  ?>
                  Cart <span class="badge" id="comparison-count"><?php echo $cart_count; ?></span>
              </a>
          </li>
        <!--<li><a href="#"><span class="glyphicon glyphicon-shopping-cart"></span> Cart</a></li>-->
      </ul>
    </div>
  </div>
</nav>


</body>
</html>

