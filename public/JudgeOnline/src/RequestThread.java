
import java.io.*;
import java.util.*;
import java.net.Socket;

public class RequestThread extends Thread {
  
  public Socket socket;
  public long usuario;
  public float time_limit;
  public long ejercicio;
  public File dir;
  ConexionDB conexion;
  public String envio;
  
  public RequestThread(Socket s) {
    this.socket = s;
  }
  
  @Override
  public void run() {
    try {

      //obtenemos el id del envio realizado
      envio = get_string_from_stream(socket.getInputStream());
      System.out.println("-------------");
      System.out.println("Se esta evaluando el envio = " + envio);

      //creamos la carpeta del envio
      dir = new File("envio/" + envio);
      if (dir.mkdirs()) {
      } else {
        System.exit(0);
      }

      //creamos la conexion con la bd para obtener los datos del envio
      conexion = new ConexionDB(envio);

      //conseguimos toda la informacion del envio 
      HashMap<String, String> datos = conexion.get_info_envio();
      datos.put("path", dir.getAbsolutePath());
      
      Language l = null;
      String language = datos.get("lenguaje");

      //creamos el objeto dependiendo del lenguaje seleccionado
      if (language.equals("java")) {
        l = new Java(datos);
      } else if (language.equals("c++")) {
        l = new Cpp(datos);
      }

      //compilamos el archivo
      l.compile();
      String errors = RequestThread.read_file(datos.get("path") + "/err.txt");

      //verificamos si hubo errores
      if (has_error(errors)) {
        conexion.set_veredict("compilation error");
        conexion.set_message(errors);
      } else {
        //ejecutamos el archivo
        String e = l.execute(); //devuelve un string si hubo un error
        if (l.time_out) {
          conexion.set_veredict("time limit");
          conexion.set_execution_time(datos.get("time_limit"));
          if (Main.so == 0) {
            Runtime rt = Runtime.getRuntime();
            System.out.println("se va a ejecutar el comando ps aux | grep 'java Main'  | awk '{print $2}' | xargs kill -9 ");
            Process p1 = rt.exec("ps aux | grep 'java Main'  | awk '{print $2}' | xargs kill -9"); //elimina por si quedo algo
            System.out.println(RequestThread.get_string_from_stream(p1.getInputStream()));
          }
          
        } else if (l.runtime_error) {
          conexion.set_veredict("runtime error");
          conexion.set_message(e);
        } else if (datos.get("test").equals("0")) { //si no es un ejercicio de prueba
          conexion.set_execution_time("" + l.execution_time);
          RequestThread.write_in_file(datos.get("path") + "/solution.txt", datos.get("out"));
          String out = RequestThread.read_file(datos.get("path") + "/" + "solution.txt");
          String output = RequestThread.read_file(datos.get("path") + "/" + "out" + l.solution + ".txt");

          //evaluamos las respuestas
          if (this.same_output(out, output)) {
            conexion.set_veredict("accepted");
          } else {
            conexion.set_veredict("wrong answer");
          }
          
        } else if (datos.get("test").equals("1")) { //si es un ejercicio de prueba
          String out = RequestThread.read_file(datos.get("path") + "/" + "out" + l.solution + ".txt");
          conexion.set_message(out);
        }
      }
      System.out.println("----fin del envio " + envio + "--------");
     // ExecuterThread.executing = false;

      //boramos todos los archivos --Esta 
      RequestThread.delete_directory(dir);// esta linea se tiene que descomentar cuando todo este bien
    } catch (Exception ex) {
      ex.printStackTrace();
      
    } finally {
      ExecuterThread.executing = false;
    }
    
    
  }

  //funcion que verifica si el error generado es valido o no
  public boolean has_error(String s) {
    
    if (s.equals("")) {
      return false;
    }
    //problem of -Xlint
    CharSequence cs = "Xlint";
    
    if (s.contains(cs)) {
      return false;
    }
    
    return true;
  }

  //comparar outputs
  //retorna si el out y el ouput(usuario) son iguales
  public boolean same_output(String out, String output) {
    
    out = out.trim();
    output = output.trim();
    
    if (out.length() != output.length()) {
      return false;
    }
    
    for (int i = 0; i < out.length(); i++) {
      if (!("" + out.charAt(i)).trim().equals(("" + output.charAt(i)).trim())) {
        return false;
      }
    }
    
    return true;
    
  }

  //retorna el string dado a partir de un InputStream
  public static String get_string_from_stream(InputStream is) {
    StringBuilder sb = new StringBuilder();
    try {
      BufferedReader br = new BufferedReader(new InputStreamReader(is));
      String s = "";
      while ((s = br.readLine()) != null) {
        sb.append(s);
      }
      br.close();
    } catch (Exception e) {
      System.err.println("Error leyendo un stream ...");
      e.printStackTrace();
    }
    
    return sb.toString().trim();
    
  }

  //escribe en un archivo un texto()
  public static void write_in_file(String path_to_file, String text) {
    try {
      BufferedWriter out = new BufferedWriter(new OutputStreamWriter(new FileOutputStream(path_to_file)));
      out.write(text);
      out.close();
    } catch (Exception e) {
      System.err.println("Error escribiendo en archivo");
      e.printStackTrace();
    }
    
  }

  //lee el contenido de un archivo
  public static String read_file(String path_to_file) {
    
    StringBuilder sb = new StringBuilder("");
    try {
      BufferedReader br = new BufferedReader(new FileReader(new File(path_to_file)));
      String s = "";
      while ((s = br.readLine()) != null) {
        sb.append(s).append("\n");
      }
      br.close();
    } catch (Exception e) {
      System.err.println("Error escribiendo en archivo");
      e.printStackTrace();
    }
    
    return sb.toString();
    
  }

  //elimina un directorio
  public static boolean delete_directory(File directory) {
    if (directory.exists()) {
      File[] files = directory.listFiles();
      if (null != files) {
        for (int i = 0; i < files.length; i++) {
          if (files[i].isDirectory()) {
            delete_directory(files[i]);
          } else {
            files[i].delete();
          }
        }
      }
    }
    return (directory.delete());
  }
}
