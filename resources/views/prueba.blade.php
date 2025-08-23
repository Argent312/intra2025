<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css" rel="stylesheet" />
    <script type="module">
	    import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';

	    createChat({
		    webhookUrl: 'http://localhost:5678/webhook/e62d2a60-9888-48b1-8b02-6c6fd2faadd0/chat'
	    });
</script>
</head>
<body>
    
</body>
</html>