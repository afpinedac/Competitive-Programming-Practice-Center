<script languaje="JavaScript">
function getTime() {
now = new Date();
y2k = new Date("{evaluacion::find($evaluacion)->get_time_fin()|date_format:"%b %d %Y %H:%M:%S"}"); //"Dec 31 2012 23:59:59"
days = (y2k - now) / 1000 / 60 / 60 / 24;
daysRound = Math.floor(days);
hours = (y2k - now) / 1000 / 60 / 60 - (24 * daysRound);
hoursRound = Math.floor(hours);
minutes = (y2k - now) / 1000 /60 - (24 * 60 * daysRound) - (60 * hoursRound);
minutesRound = Math.floor(minutes);
seconds = (y2k - now) / 1000 - (24 * 60 * 60 * daysRound) - (60 * 60 * hoursRound) - (60 * minutesRound);
secondsRound = Math.round(seconds);
sec = (secondsRound == 1) ? " segundo " : " segundos";
min = (minutesRound == 1) ? " minuto " : " minutos, ";
hr = (hoursRound == 1) ? " hora " : " horas, ";
dy = (daysRound == 1) ? " día " : " d&iacute;as, ";
text = "Quedan ";

if(daysRound>0){
    text +=  daysRound + dy + hoursRound + hr + minutesRound + min + secondsRound + sec 
}else if(hoursRound>0){
    text +=  hoursRound + hr + minutesRound + min + secondsRound + sec 
}else if(minutesRound>0){
    text += minutesRound + min + secondsRound + sec 
}else if(secondsRound > 1){ 
    text += secondsRound + sec 
}else{
    alert('El tiempo se ha agotado');
  location.href = "{url('curso/ver')}"+ "/" + "{$curso->id}" + "/contenido";
}


document.getElementById("counter").innerHTML = text;


newtime = window.setTimeout("getTime();", 1000);
}
getTime();
</script>