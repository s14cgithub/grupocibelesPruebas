
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
	
	<style>
	
	.container {
    max-width: 750px;
}
 
.btn {
    background-color: rgb(11, 165, 192);
    color: #fff;
}
 
.btn:hover,
.btn:active {
    background-color: rgb(15, 132, 153);
    color: #fff;
    transition: all 0.2s;
}
 
.btn:focus {
    box-shadow: none;
}
	
	
	</style>
    <title>Live chat</title>
</head>
<body>
	<div class="container my-4">
        <h3 class="my-4 text-center">Live chat</h3>
        <!-- buttons for chatrooms -->
        <div class="chat-rooms mb-3 text-center">
            <div class="my-2">Choose a chatroom:</div>
            <button class="btn my-1" id="general">#general</button>
            <button class="btn my-1" id="foodies">#foodies</button>
            <button class="btn my-1" id="tv-shows">#TV shows</button>
            <button class="btn my-1" id="music">#music</button>
        </div>
        <!-- chat window / conversations -->
        <!-- new chat form (message) -->
        <!-- update name form -->
		
		<!-- chat window / conversations -->
        <div class="chat-window">
            <ul class="chat-list list-group"></ul>
        </div>
        <!-- new chat form (message) -->
        <form class="new-chat my-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">Say something :</div>
                </div>
                <input type="text" id="message" class="form-control" required>
                <div class="input-group-append">
                    <input type="submit" class="btn" value="send">
                </div>
            </div>
        </form>
        <!-- update name form -->
        <form class="new-name my-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">Update name:</div>
                </div>
                <input type="text" id="name" class="form-control" required>
                <div class="input-group-append">
                    <input type="submit" class="btn" value="update">
                </div>
            </div>
            <div class="update-message"></div>
        </form>
        
    </div>
	
	
	
	
	
	
    
    <script src="scripts/chat.js"></script>
    <script src="scripts/ui.js"></script>
    <script src="scripts/app.js"></script>
</body>
</html>