<?php 
global $table_prefix, $wpdb;
try
{
    if(isset($_POST["product_name"]))
    {
        $_POST["product"]["name"] = $_POST["product_name"];
    }

    if(isset($_POST["product_image"]))
    {
        $_POST["product"]["image"] = $_POST["product_image"];
    }

    if(isset($_POST["product_price"]))
    {
        $_POST["product"]["price"] = $_POST["product_price"] * 100;
    }

    /*if(isset($_POST["product_plan_id"]))
    {
        $_POST["product"]["plan_id"] = $_POST["product_plan_id"];
    }*/

    if(isset($_POST["product_currency"]))
    {
        $_POST["product"]["currency"] = $_POST["product_currency"];
    }

    if(isset($_POST["product_status"]))
    {
        $_POST["product"]["status"] = $_POST["product_status"];
    }

    if(isset($_POST["product_desc"]))
    {
        $_POST["product"]["desc"] = $_POST["product_desc"];
    }

    if(!isset($_POST["product"]))
    {
        throw new Exception("invalid request URL.");
    }

    if(!isset($_POST["product"]["image"]))
    {
        throw new Exception("Image is not set.");
    }

    if(trim($_POST["product"]["image"]) == "newimage")
    {
        if(!isset($_FILES["fileToUpload"]["tmp_name"]))
        {
            throw new Exception("Please select an image.", 1);
            
        }

        $target_file = getTekzoneMediaProductBasePath(basename($_FILES["fileToUpload"]["name"]));
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        $imageName = explode(".", basename($target_file));

        $newfilename = preg_replace("/[^a-zA-Z0-9_-]+/", "_", $imageName[0]).".".$imageName[1];
        $target_file = getTekzoneMediaProductBasePath($newfilename);


        if(getimagesize($_FILES["fileToUpload"]["tmp_name"]) === false)
        {
            throw new Exception("File is not an image.");
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" )
        {
            throw new Exception("only JPG, JPEG, PNG & GIF files are allowed.");
        }

        if(file_exists($target_file) && !is_dir($target_file))
        {
            $_POST["product"]["image"] = basename($target_file);
        }
        else
        {
            if($_FILES["fileToUpload"]["size"] > 500000)
            {
                throw new Exception("file is too large.");   
            }

            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
            {
                $_POST["product"]["image"] = basename($target_file);
            } else {
                throw new Exception("there was an error uploading your file.");
            }
        }
    }

    $productTable = $table_prefix . 'tekzone_zoolz_products';
    $product = $wpdb->get_row("SELECT * FROM `".$productTable."` WHERE id='".(int)$_POST["id"]."'");

    if(!$product->id)
    {
        throw new Exception("Product id is not valid.");   
    }

    $_POST["product"]["id"] =$product->id;


    $errors = array();
    if(!isset($_POST["product"]["name"]))
    {
        $errors["name"] = "Please enter name.";
    }
    elseif(!$_POST["product"]["name"] = trim($_POST["product"]["name"]))
    {
        $errors["name"] = "Please enter name.";
    }
    elseif(!filter_var($_POST["product"]["name"], FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9-&$#@!()*^?%_\s]+$/")))){
        $errors["name"] = "Please enter a valid name.";
    }

    if(!isset($_POST["product"]["currency"]))
    {
        $errors["currency"] = "Please enter currency.";
    }
    elseif(!$_POST["product"]["currency"] = trim($_POST["product"]["currency"]))
    {
        $errors["currency"] = "Please enter currency.";
    }


    if(!isset($_POST["product"]["status"]))
    {
        $errors["status"] = "Please enter status.";
    }
    elseif(!$_POST["product"]["status"] = trim($_POST["product"]["status"]))
    {
        $errors["status"] = "Please select status.";
    }


    if(!isset($_POST["product"]["desc"]))
    {
        $errors["desc"] = "Please enter desc.";
    }
    elseif(!$_POST["product"]["desc"] = trim($_POST["product"]["desc"]))
    {
        $errors["desc"] = "Please select desc.";
    }


    if(!isset($_POST["product"]["image"]))
    {
        $errors["image"] = "Please enter image.";
    }
    elseif(!$_POST["product"]["image"] = trim($_POST["product"]["image"]))
    {
        $errors["image"] = "Please select image.";
    }

    $target_file = getTekzoneMediaProductBasePath($_POST["product"]["image"]);
    if(!file_exists($target_file) || is_dir($target_file))
    {
        $errors["image"] = "Please select valid image.";
    }


    if(!isset($_POST["product"]["price"]))
    {
        $errors["price"] = "Please enter price.";
    }
    elseif((int)$_POST["product"]["price"] < 0)
    {
        $errors["price"] = "Please enter valid price.";
    }


    /*if(!isset($_POST["product"]["plan_id"]))
    {
        $errors["plan_id"] = "Please enter plan_id.";
    }
    elseif(!(int)$_POST["product"]["plan_id"])
    {
        $errors["plan_id"] = "Please enter valid plan id.";
    }*/

    if(count($errors))
    {
        throw new Exception(implode('<br>', $errors));
    }


    $sql = "UPDATE `".$productTable."` SET name='".$_POST["product"]["name"]."', image='".$_POST["product"]["image"]."', price_in_cents='".$_POST["product"]["price"]."', currency='".$_POST["product"]["currency"]."', status='".$_POST["product"]["status"]."', `desc`='".$_POST["product"]["desc"]."' WHERE id='".$_POST["product"]["id"]."'";

    $result = $wpdb->query($wpdb->prepare($sql));

}
catch(Exception $e)
{
    $_SESSION["tekzone_zoolz_message"]["error"] = $e->getMessage();
}

wp_redirect(admin_url( 'admin.php?page=zoolz-products' ));
?>