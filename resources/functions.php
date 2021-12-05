<?php 

// helper functions

function set_message($msg)
{
    if(!empty($msg)){
        $_SESSION['message'] = $msg;
    }else {
        $msg = "";
    }
}

function display_message()
{
    if(isset($_SESSION['message'])){
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}

function redirect($location)
{
    header("Location: $location");
}

function query($sql)
{
    global $connection;
    return mysqli_query($connection, $sql);
}

function confirm($result)
{
    global $connection;
    if (!$result) {
        die("Error en la conexión" . mysqli_error($connection));
    }
}

//To prevent injections to DB
function escape_string($string)
{
    global $connection;
    return mysqli_real_escape_string($connection, $string);
}

function fetch_array($result)
{
    return mysqli_fetch_array($result);
}

//*******************FRONTEND FUNCTIONS******* */

//get products

function get_products()
{
    $query = query("SELECT * FROM products");
    confirm($query);

    while ($row = fetch_array($query)) {
        
        //heredoc - Nowdoc
        $product = <<<DELIMETER

        <div class="col-sm-4 col-lg-4 col-md-4">
            <div class="thumbnail">
                <a href="item.php?id={$row['product_id']}"><img src={$row['product_image']} alt=""></a>
                <div class="caption">
                    <h4 class="pull-right">&#36;{$row['product_price']}</h4>
                    <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                    </h4>
                    <p>See more snippets like this online store item at <a target="_blank" href="http://www.bootsnipp.com">Bootsnipp - http://bootsnipp.com</a>.</p>
                    <a class="btn btn-primary" target="_blank" href="../resources/cart.php?add={$row['product_id']}">Add to cart</a>
                </div>                
            </div>
        </div>

        DELIMETER;

        echo $product;
    }
}

function get_categories() 
{
    $query = query("SELECT * FROM categories");
    confirm($query);

    while($row = fetch_array($query)){

        $categories_links = <<<DELIMETER

        <a href='category.php?id={$row['cat_id']}' class='list-group-item'>{$row['cat_title']}</a>
        
        DELIMETER;

    echo $categories_links;
        
    }
}

function get_products_in_cat_page()
{
    $query = query("SELECT * FROM products WHERE product_category_id = " . escape_string($_GET['id']) . " ");
    confirm($query);

    while ($row = fetch_array($query)) {
        
        //heredoc - Nowdoc
        $product = <<<DELIMETER

        <div class="col-sm-4 col-lg-4 col-md-4">
            <div class="thumbnail">
                <a href="item.php?id={$row['product_id']}"><img src={$row['product_image']} alt=""></a>
                <div class="caption">
                    <h4 class="pull-right">&#36;{$row['product_price']}</h4>
                    <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                    </h4>
                    <p>See more snippets like this online store item at <a target="_blank" href="http://www.bootsnipp.com">Bootsnipp - http://bootsnipp.com</a>.</p>
                    <a class="btn btn-primary" target="_blank" href="item.php?id={$row['product_id']}">Add to cart</a>
                </div>                
            </div>
        </div>

        DELIMETER;

        echo $product;
    }
}

function get_products_in_shop_page()
{
    $query = query("SELECT * FROM products");
    confirm($query);

    while ($row = fetch_array($query)) {
        
        //heredoc - Nowdoc
        $product = <<<DELIMETER

        <div class="col-sm-4 col-lg-4 col-md-4">
            <div class="thumbnail">
                <a href="item.php?id={$row['product_id']}"><img src={$row['product_image']} alt=""></a>
                <div class="caption">
                    <h4 class="pull-right">&#36;{$row['product_price']}</h4>
                    <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
                    </h4>
                    <p>See more snippets like this online store item at <a target="_blank" href="http://www.bootsnipp.com">Bootsnipp - http://bootsnipp.com</a>.</p>
                    <a class="btn btn-primary" target="_blank" href="item.php?id={$row['product_id']}">Add to cart</a>
                </div>                
            </div>
        </div>

        DELIMETER;

        echo $product;
    }
}

function login_user()
{
    if (isset($_POST['submit'])){
        $username = escape_string($_POST['username']);
        $password = escape_string($_POST['password']);

        $query = query("SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}' ");
        confirm($query);

        if(mysqli_num_rows($query) == 0){

            set_message("Your username or password are wrong!");
            redirect("login.php");
        }else {
            $_SESSION['username'] = $username;
            redirect("admin");
        }

    }
}

// function send_message()
// {
//     if(isset($_POST['submit'])){

//         $to = "pjmorante@gmail.com";
//         $name = $_POST['name'];
//         $subject = $_POST['subject'];
//         $email = $_POST['email'];
//         $message = $_POST['message'];

//         $headers = "From: {$name} {$email}";

//         $result = mail($to, $subject, $message, $headers);

//         if(!$result){
//             set_message("Sorry we could not send your message!");
//             redirect("contact.php");
//         }else {
//             set_message("Your message has been sent!");
//             redirect("contact.php");
//         }
//     }
// }

function contact() {

    if(isset($_POST['submit'])) {

        $name = escape_string($_POST['name']);
        $email = escape_string($_POST['email']);
        $phone = escape_string($_POST['phone']);
        $subject = escape_string($_POST['subject']);
        $message = escape_string($_POST['message']);

        $query = query("INSERT INTO contact(name, email, phone, subject, message) VALUES('{$name}', '{$email}', '{$phone}', '{$subject}', '{$message}')");
        confirm($query);       
        //redirect("index.php?products");
    }
}

//*******************BACKEND FUNCTIONS******* */
function display_orders(){

    $query = query("SELECT * FROM orders");
    confirm($query);

    while($row = fetch_array($query)) {

        $orders = <<<DELIMETER

            <tr>
                <td>{$row['order_id']}</td>
                <td>{$row['order_amount']}</td>
                <td>{$row['order_transaction']}</td>
                <td>{$row['order_currency']}</td>
                <td>{$row['order_status']}</td>
                <td><a class="btn btn-danger" href="index.php?delete_order_id={$row['order_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
            </tr>

        DELIMETER;
        echo $orders;
    }

}

//*******ADMIN************************************* */

function get_products_in_admin(){

    $query = query(" SELECT * FROM products");
    confirm($query);

    while($row = fetch_array($query)) {

        $category = show_product_category_title($row['product_category_id']);

        $product_image = display_image($row['product_image']);

        $product = <<<DELIMETER

                <tr>
                    <td>{$row['product_id']}</td>
                    <td>{$row['product_title']}<br>
                <a href="index.php?edit_product&id={$row['product_id']}"><img width='100' src="../../resources/{$product_image}" alt=""></a>
                    </td>
                    <td>{$category}</td>
                    <td>{$row['product_price']}</td>
                    <td>{$row['product_quantity']}</td>
                    <td><a class="btn btn-danger" href="index.php?delete_product_id={$row['product_id']}"><span class="glyphicon glyphicon-remove"></span></a></td>
                </tr>

        DELIMETER;
        echo $product;
    }
}

function add_product() {

    if(isset($_POST['publish'])) {

        $product_title          = escape_string($_POST['product_title']);
        $product_category_id    = escape_string($_POST['product_category_id']);
        $product_price          = escape_string($_POST['product_price']);
        $product_description    = escape_string($_POST['product_description']);
        $short_desc             = escape_string($_POST['short_desc']);
        $product_quantity       = escape_string($_POST['product_quantity']);
        $product_image          = escape_string($_FILES['file']['name']);
        $image_temp_location    = escape_string($_FILES['file']['tmp_name']);

        move_uploaded_file($image_temp_location  , UPLOAD_DIRECTORY . DS . $product_image);

        $query = query("INSERT INTO products(product_title, product_category_id, product_price, product_description, short_desc, product_quantity, product_image) VALUES('{$product_title}', '{$product_category_id}', '{$product_price}', '{$product_description}', '{$short_desc}', '{$product_quantity}', '{$product_image}')");
        $last_id = last_id();
        confirm($query);
        set_message("New Product with id {$last_id} was Added");
        redirect("index.php?products");
    }
}



?>