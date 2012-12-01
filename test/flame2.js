// window.onload = function(){
// 	animateFastestSubmission('00:09:00','1');	
// }

function animateFastestSubmission(time, failedAttempts, isgray, linkurl){
	var canvas = document.getElementById("fastest-submit");
	if(!canvas)
		return;
	var ctx = canvas.getContext("2d");
	var W = 110, H = 40;

	var linkX=W/2-48/2;
	var linkY=H/2+1.8;
	var linkHeight=12;
	// var linkWidth=48;
	var linkWidth=ctx.measureText(time).width;
	var inLink = false;

	
	canvas.width = W;
	canvas.height = H;
	
	var particles = [];
	
	var particle_count = 8;
	for(var i = 0; i < particle_count; i++)
	{
		particles.push(new particle());
	}
	canvas.addEventListener("mousemove", on_mousemove, false);
    canvas.addEventListener("click", on_click, false);
	
	function particle()
	{
		//speed, life, location, life, colors
		//speed.x range = -2.5 to 2.5 
		//speed.y range = -15 to -5 to make it move upwards
		//lets change the Y speed to make it look like a flame
		this.speed = {x: -2+Math.random()*4, y: -2+Math.random()*1};
		
		//Now the flame follows the mouse coordinates
		var range = 20;
		this.location = {x: W/2+ -range+Math.random()*range*2, y: H/2};
		//radius range = 10-30
		this.radius = 5+Math.random()*10;
		//life range = 20-30
		this.life = 20+Math.random()*20;
		this.remaining_life = this.life;
		//colors
		// this.r = Math.round(Math.random()*255);
		// this.g = Math.round(Math.random()*255);
		// this.b = Math.round(Math.random()*255);
		// this.r = Math.round(200+Math.random()*50);
		// this.g = Math.round(200+Math.random()*50);
		// this.b = Math.round(100);
		if(Math.round(Math.random()*8)==2){
			this.r = Math.round(255);
			this.g = Math.round(140);
			this.b = Math.round(0);	
		}else{
			this.r = Math.round(200+Math.random()*50);
			this.g = Math.round(200+Math.random()*50);
			this.b = Math.round(100);
		}
		
		 // , 245, 238)
		// 241
	}
	
	function draw()
	{
		//Painting the canvas black
		//Time for lighting magic
		//particles are painted with "lighter"
		//In the next frame the background is painted normally without blending to the 
		//previous frame
		ctx.globalCompositeOperation = "source-over";
		ctx.fillStyle = isgray==0?"#f1f0f0":"white"; //gray or white depending on
		ctx.fillRect(0, 0, W, H);

		ctx.fillStyle = '#390';
		ctx.textBaseline = 'center';
		ctx.font = 'bold 12px "Helvetica Neue", Helvetica, Arial, sans-serif';
		ctx.fillText(time, linkX, linkY);

		ctx.fillStyle = 'black';
		ctx.font = '10px "Helvetica Neue", Helvetica, Arial, sans-serif';
		ctx.fillText(failedAttempts, W/2-70/2, H/2+10);
		ctx.globalCompositeOperation = "darker";
		
		for(var i = 0; i < particles.length; i++)
		{
			var p = particles[i];
			ctx.beginPath();
			//changing opacity according to the life.
			//opacity goes to 0 at the end of life of a particle
			p.opacity = Math.round(p.remaining_life/p.life*100)/100
			//a gradient instead of white fill
			var gradient = ctx.createRadialGradient(p.location.x, p.location.y, 0, p.location.x, p.location.y, p.radius);
			gradient.addColorStop(0, "rgba("+p.r+", "+p.g+", "+p.b+", "+p.opacity+")");
			gradient.addColorStop(0.5, "rgba("+p.r+", "+p.g+", "+p.b+", "+p.opacity+")");
			gradient.addColorStop(1, "rgba("+p.r+", "+p.g+", "+p.b+", 0)");
			ctx.fillStyle = gradient;
			ctx.arc(p.location.x, p.location.y, p.radius, Math.PI*2, false);
			ctx.fill();
			
			//lets move the particles
			p.remaining_life--;
			p.radius--;
			p.location.x += p.speed.x;
			p.location.y += p.speed.y;
			
			//regenerate particles
			if(p.remaining_life < 0 || p.radius < 0)
			{
				//a brand new particle replacing the dead one
				// particles[i].
				particles[i] = new particle();
			}
		}
	}
	
	setInterval(draw, 35);

	function on_mousemove (ev) {

		var x,y;

		if(ev.offsetX) {
			x = ev.offsetX;
			y = ev.offsetY;
		}
		else if(ev.layerX) {
			x = ev.layerX;
			y = ev.layerY;
		}
		x-=canvas.offsetLeft;
		y-=canvas.offsetTop;

		// console.log(x+" "+y+"  "+linkX+" "+linkWidth);
	 // console.log(x);
	  //is the mouse over the link?
	  if(x>=linkX && x <= (linkX + linkWidth) &&
	  	y<=linkY && y>= (linkY-linkHeight)){
	  	document.body.style.cursor = "pointer";
	  	inLink=true;
		}
		else{
			document.body.style.cursor = "";
			inLink=false;
		}
	}
	
	function on_click(e) {
	    if (inLink)  {
	    window.location = linkurl;
	 }
}
}

