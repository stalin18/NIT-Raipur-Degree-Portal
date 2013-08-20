<script type="text/javascript">

step=1
timerDown="" 

onload=function scrollDivDown(){
el=document.getElementById("scroll")
clearTimeout(timerDown) 
el.scrollTop+=step
timerDown=setTimeout("scrollDivDown()",10)

if(el.scrollTop>=el.scrollHeight-el.offsetHeight){
el.scrollTop=0
}

}

</script> 