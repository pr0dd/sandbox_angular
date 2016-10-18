//Basic webserver

//Initialize variables:
var express = require("express"),
	app = express();

//Middleware:
app.set("port", process.env.PORT || 3000);
app.use("/assets", express.static(__dirname + "/assets"));
app.use("/app", express.static(__dirname + "/app"));

//Routes:
app.get("/", function(req, res){
	res.sendFile("index.html", {root: __dirname}, function(err){
		if (err) {
	    	console.log(err);
	    	res.status(err.status).end();
	    } else {
	    	console.log('Sent: index.html');
	    }
	});
});

//Start server:
app.listen(app.get("port"), function(){
	console.log(`Express server is running on port ${app.get("port")}; Press ctrl+C to terminate.`);
});