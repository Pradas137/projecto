
  function ParoImpar(){

    var num = parseInt(document.getElementById("ParoImpar").value);
    if((num%3==0) && (num%7==0)){
      document.getElementById("resultado").innerHTML="Es divisible por 3 y 7";
    }else{
      document.getElementById("resultado").innerHTML="No es divisible ";
    }
  }


function PositivoiNegativo(){
  var numero = parseInt(document.getElementById("PositivoiNegativo").value);
            if(numero>0)
                document.getElementById("resultado2").innerHTML="es positivo";
            else if(numero<0)
                document.getElementById("resultado2").innerHTML="es negativo";
            else if(numero==0)
                document.getElementById("resultado2").innerHTML="es 0";

}


function ponerCeros(){
  var numero = document.getElementById("ponerCeros").value;

  var longitud = numero.length;
  if (longitud == 1){
    document.getElementById("resultado4").innerHTML="0000" + numero;
  }
  else if (longitud  == 2){
    document.getElementById("resultado4").innerHTML="000" + numero;
  }
  else if (longitud  == 3){
    document.getElementById("resultado4").innerHTML="00" + numero;
  }
  else if (longitud  == 4){
    document.getElementById("resultado4").innerHTML="0" + numero;
  }
  else if (longitud  == 5){
    document.getElementById("resultado4").innerHTML= numero;
  }
}