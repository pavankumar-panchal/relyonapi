
function start() 
{
   new mq('flashnews');
   mqRotate(mqr); // must come last
}
window.onload = start;

function startstopchange(m,txt) {}  

function startstop(m,n) 
{
	var ss = document.createElement('form');
	var sd = document.createElement('div');
	ss.appendChild(sd);
	n.parentNode.insertBefore(ss,n);
}   



function objHeight(obj) 
{
	if(obj.offsetHeight) 
		return  obj.offsetHeight; 
	if (obj.clip) 
		return obj.clip.height; 
	return 0;
} 
var mqr = [];
var topgap = 200;

function mq(id)
{
	this.mqo=document.getElementById(id); 
	var ht = objHeight(this.mqo.getElementsByTagName('div')[0])+ 5; 
	var fulht = objHeight(this.mqo);
	var txt = this.mqo.getElementsByTagName('div')[0].innerHTML;
	this.mqo.innerHTML = ''; 
	var wid = this.mqo.style.width; 
	this.mqo.onmouseout=function() 
	{
		mqRotate(mqr);
		startstopchange(mqr,'');
	};
	this.mqo.onmouseover=function() 
	{
		clearTimeout(mqr[0].TO); 
		startstopchange(mqr,'');
	};
	this.mqo.ary=[]; 
	var maxw = Math.ceil(fulht/ht)+1;
	for (var i=0;i < maxw;i++)
	{
		this.mqo.ary[i]=document.createElement('div'); 
		this.mqo.ary[i].innerHTML = txt;
		this.mqo.ary[i].style.position = 'absolute';
		this.mqo.ary[i].style.top = (ht*i)+'px'; 
		this.mqo.ary[i].style.marginTop = topgap+'px'; 
		this.mqo.ary[i].style.height = ht+'px';
		this.mqo.ary[i].style.width = wid; 
		this.mqo.appendChild(this.mqo.ary[i]);
	} 
	mqr.push(this.mqo);
	startstop(mqr,this.mqo);
} 


function mqRotate(mqr)
{
	if (!mqr) return; 
	for (var j=mqr.length - 1; j > -1; j--)
	{
		maxa = mqr[j].ary.length; 
		for (var i=0;i<maxa;i++)
		{
			var x = mqr[j].ary[i].style; 
			x.top=(parseInt(x.top,10)-1)+'px'; 
		}
		var y = mqr[j].ary[0].style; 
		if (parseInt(y.top,10)+parseInt(y.height,10)+topgap<0) 
		{
			var z = mqr[j].ary.shift();
			z.style.top = (parseInt(z.style.top) + parseInt(z.style.height)*maxa) + 'px'; 
			mqr[j].ary.push(z);
		}
	} 
	mqr[0].TO=setTimeout('mqRotate(mqr)',30);
}