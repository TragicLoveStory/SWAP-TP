<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
</head>
<body>
  <?php 
  if(!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
      echo "Not logged in.";
      die();
  }
  ?>
<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 5"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
        <img src="Images/amc2.jpg" class="d-block w-100 myCarouselDiv">
        </div>
        <div class="carousel-item">
        <img src="Images/amc3.jpg" class="d-block w-100 myCarouselDiv">
        </div>
        <div class="carousel-item">
        <img src="Images/amc4.jpg" class="d-block w-100 myCarouselDiv">
        </div>
        <div class="carousel-item">
        <img src="Images/amc5.jpg" class="d-block w-100 myCarouselDiv">
        </div>
        <div class="carousel-item">
        <img src="Images/amc6.jpg" class="d-block w-100 myCarouselDiv">
        </div>
</div>
<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
</button>
<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
</button>
</div>
<style>
    .myCarouselDiv{
        max-height: 750px;
        object-fit: cover;
    }
</style>
</body>
</html>