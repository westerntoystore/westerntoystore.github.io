<!DOCTYPE html>
<?php require_once('config.php'); ?>

<?php 
  /*if(isset($_SESSION["sess_user"])){
    echo "<script>";
    echo "document.getElementById('login').style.display = 'none';";
    echo "</script>";
  }*/
  try{
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql_select = "select * from product2 order by id";
    $user_info = $pdo->query($sql_select);

    #$pdo = null;
  }

  catch (PDOException $e) {    
    die( $e->getMessage() ); 
  } 
?>

<html>

<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../Add_ons/css/common.css">
  <link rel="stylesheet" href="../Add_ons/bootstrap_4_0/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

  <script src="../Add_ons/js/common.js"></script>
  
  <link rel="stylesheet" type="text/css" href="../Add_ons/css/action.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>

<body>
  <div class="preload">
    <img src="../img/loading.gif" alt="Loading" />
  </div>
  
  <div class="web-content">
    <!--Nav bar begin-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-cust static-top">
      <div class="container">
        <a class="navbar-brand" href="#">
              <img src="../img/logo.png" alt="logo">
            </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">

            <li class="nav-item">
              <a class="nav-link" href="../index.php">Home</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="about.php">About</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="catlog.php">Puzzels</a>
            </li>

            <li class="nav-item active">
              <a class="nav-link" href="action.php">Action</a>
            </li>

              <li class="nav-item">
                <a class="nav-link" data-toggle="tooltip" data-placement="bottom" title="Cart"href="Cart.php"><i class="fa fa-shopping-cart"></i></a>
              </li>

              <?php
              if(isset($_SESSION['sess_user'])){
                echo"</ul>";
                echo'<ul class="navbar-nav">';
                  echo'<!-- Dropdown -->';
                  echo'<li class="nav-item dropdown">';
                   echo'<a class="nav-link dropdown-toggle" id="login" href="#" id="navbardrop" data-toggle="dropdown">'.$_SESSION['sess_user']."</a>";
                   echo'<div id="drop-down" class="dropdown-menu" >';
                    echo'<a class="dropdown-item" href="src/logout.php">Logout</a>';

                   echo'</div>';
                  echo'</li>';
                  echo'</ul>';
              }
              else{
                echo '<li class="nav-item">';
                  echo '<a class="nav-link" id="login" href="account.php">Login/Register</a>';
                echo '</li>';
                echo "</ul>";
              }
            ?>

          </ul>
        </div>
      </div>
    </nav>

    <!--Nav bar END-->

  <div class="container">
    <!--h2>Card Image</h2-->
    <div class="row">
    <!---<p>Image at the top (card-img-top):</p>-->
    <?php
      while ($row = $user_info->fetch() ) {
        $trackid = $row['id'];
                  
          echo '<div class="col-md-4 col-lg-4">';
           echo  '<div class="card">';
            echo '<img class="card-img-top" src="..//'  .$row['src'] . '" alt="Card image" >';
           echo '<div class="card-body">';

            echo '<h4 class="card-title">'.$row['name'] .'</h4>';
              echo '<p class="card-text">'.$row['description'] .'</p>';
              echo '<a href="?id='.$trackid.'" class="btn btn-primary">Add to Cart</a>';
              if (isset($_GET['id'])) {
                if ($_GET['id'] ==$row['id']){
                  try {
                    if(isset($_SESSION['sess_user'])){
                      $pieces = explode(" ", $_SESSION['sess_user']);
                      $statement = $pdo->prepare('INSERT INTO ' .$pieces[0]. 'cart (id, name, src, price, quantity, soldby, description) VALUES (?, ?, ?, ?,?,?,?)');
                    }
                    else{
                      $statement = $pdo->prepare('INSERT INTO cart (id, name, src, price, quantity, soldby, description) VALUES (?, ?,?, ?, ?,?,?)');
                    }
                    $statement->execute([$row['id'], $row['name'], $row['src'], $row['price'], '1' , $row['soldby'], $row['description'] ]);
                    echo "<script>";
                      echo "alert('Added to cart!!!')";
                    echo "</script>";
                  }
                  catch (PDOException $e) {
                    if ($e->errorInfo[1] == 1062) {
                        //echo "Item already in the cart";
                        echo "<script>";
                          echo "alert('Already Added to cart!!!')";
                        echo "</script>";
                    }
                  }
                }
              //if($_POST['action'] == '') {
              }
              echo'</div>';
            echo'</div>';
          echo'</div>';
        }
    ?>

  </div>
  </div>


    
    <!--Footer begin-->
    <section id="footer">
        <div class="container">
        <div class="row" style="margin-bottom: 20px;" >
            <div class="input-group mb-3" style="width: 40%; margin-left: 31%;">
              <input type="text" class="form-control" placeholder="random@example.com" aria-label="Recipient's username" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <a href="javascript:void(0);" style="text-decoration: none;"><span class="input-group-text" id="basic-addon2" style="background-color: #f15b28; color: white;">Subscribe</span></a>
              </div>
            </div>
          </div>

          <div class="row text-center text-xs-center text-sm-left text-md-left">

            <div class="col-xs-12 col-sm-4 col-md-4">
              <div class="col_one_third">
                  <div class="widget clearfix">
                      <h4>Contact Us</h4>
                      <hr>
                      <div>
                        <address>Western Toy Store, Kalamzoo, Michigan</address>
                        <abbr title="Phone Number"><strong>Phone:</strong></abbr>
                        +977-1-5186023                        <br>
                        <abbr title="Email Address"><strong>Email:</strong></abbr>
                        info@westtoy.com                    
                      </div>
                  </div>

              </div>
            </div>

            <div class="col-xs-12 col-sm-4 col-md-4" id="break">
              <div class="in-marg1">
              <h5 style="">About Western Toy </h5>
              <ul class="list-unstyled quick-links"style="">
                <li><a href="#"><i class="fa fa-angle-double-right"></i>About</a></li>
                <li><a href="#"><i class="fa fa-angle-double-right"></i>FAQ</a></li>
                <li><a href="#"><i class="fa fa-angle-double-right"></i>Retail Store Info</a></li>
                <li><a href="#"><i class="fa fa-angle-double-right"></i>Wester Toy Store is Buying</a></li>
              </ul>
              </div>
            </div>


            <div class="col-xs-12 col-sm-4 col-md-4" >
              <div class="in-marg2">
              <h5 style="">Western Toy Policies</h5>
              <ul class="list-unstyled quick-links" style="">
                <li><a href="#"><i class="fa fa-angle-double-right"></i>Shipping Policy</a></li>
                <li><a href="#"><i class="fa fa-angle-double-right"></i>Return and Cancellation</a></li>
                <li><a href="#"><i class="fa fa-angle-double-right"></i>Privacy and Security</a></li>
                <li><a href="#"><i class="fa fa-angle-double-right"></i>Pre-Orders</a></li>
              </ul>
            </div>
            </div>
          </div>
        </div>
        <div class="Footer-logo">
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-5" style="margin-top: 20px !important;">
                <ul class="list-unstyled list-inline social text-center">
                  <li class="list-inline-item"><a href="#"><i class="fa fa-facebook"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fa fa-twitter"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fa fa-instagram"></i></a></li>
                  <li class="list-inline-item"><a href="#"><i class="fa fa-google"></i></a></li>
                  <li class="list-inline-item"><a href="#" target="_blank"><i class="fa fa-envelope"></i></a></li>
                </ul>
              </div>
            </div>  

            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-2 text-center text-white">
                <p class="h6">Copyright &copy All right Reversed by<a class="text-green ml-2" href="#" target="_blank">Western Toy Store </a></p>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-2 text-center text-white">
                <p class="h6">Image Credit<a class="text-green ml-2" href="#" target="_blank">Google Images </a></p>
              </div>
          </div> 
        </div>
      </section>
    </div>

    <!-- Footer END -->
    <!-- -->
  <script src="../Add_ons/js/common.js"></script>
  <script src="../Add_ons/bootstrap_4_0/jquery/jquery.min.js"></script>
  <script src="../Add_ons/bootstrap_4_0/js/bootstrap.min.js"></script>
</body>

</html>
