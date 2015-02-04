
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileOutputStream;
import java.io.FileReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;


public class Util {
  
  
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
