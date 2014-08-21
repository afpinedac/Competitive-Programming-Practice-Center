
import java.util.*;
import java.sql.ResultSet;
import java.sql.Statement;
import java.sql.Connection;
import java.sql.DriverManager;

public class ConexionDB {

    public String host;
    public String port;
    public String database;
    public String user;
    public String pass;
    public String envio;
    Statement st;

    Connection conexion;

    public ConexionDB(String envio) {
        //conexion con MySQL

        this.host = "localhost";
        this.port = "3307";
        this.database = "lms";
        this.user = "DBCPP";
        
        this.pass =  Main.so == 0 ? "qwe123admin" : "";
        this.envio = envio;
        conexion = null;

        this.start();

    }

    public void start() {

        try {
            Class.forName("com.mysql.jdbc.Driver");
            conexion = DriverManager.getConnection("jdbc:mysql://localhost/" + this.database, this.user, this.pass);
           conexion.setAutoCommit(true);
            st = conexion.createStatement();

        } catch (Exception e) {
            System.err.println("Error cargando el driver de MySQL");
            e.printStackTrace();
        }
    }

    //realiza la consulta a la base de datos
    public ResultSet execute_select(String query) throws Exception {
        // System.out.println(query);
        return st.executeQuery(query);
    }

    //realiza un operacion DML
    public void execute_DML(String query) throws Exception {
        st.executeUpdate(query);
       // conexion.commit();
        
    }

    public HashMap<String, String> get_info_envio() throws Exception {

        String query = "SELECT * FROM lms_envio WHERE id = " + this.envio;

        ResultSet r = this.execute_select(query);
        r.next();

        String lenguaje = r.getString("lenguaje");
        String codigo = r.getString("codigo");
        String tipo = r.getString("tipo");
        String ejercicio = r.getString("ejercicio");
        String algoritmo = r.getString("algoritmo");
        String test = r.getString("test");
        String in = r.getString("in");

        HashMap<String, String> h = new HashMap<String, String>();

        String info[] = this.get_info_ejercicio(ejercicio, tipo, codigo);

        h.put("lenguaje", lenguaje);
        h.put("algoritmo", algoritmo);
        h.put("test", test);
        h.put("in", info[0]);
        h.put("out", info[1]);
        h.put("time_limit", info[2]);

        if (h.get("test").equals("1")) {
            h.put("in", in);
        }

        return h;

    }

    //funcion que retorna el out de un ejercicio
    private String[] get_info_ejercicio(String ejercicio, String tipo, String codigo) throws Exception {

        String s[] = new String[3];
        ResultSet r = this.execute_select("SELECT * FROM lms_ejercicio WHERE id = " + ejercicio);
        r.next();

        s[0] = r.getString("in");
        s[1] = r.getString("out");
        s[2] = this.get_time_limit(ejercicio, tipo, codigo);

        return s;

    }

    private String get_time_limit(String ejercicio, String tipo, String codigo) throws Exception {

        String query = "SELECT time_limit FROM ";
        if (tipo.equals("0")) {
            query += "lms_ejercicio_x_taller  WHERE taller=" + codigo;
        } else {
            query += "lms_ejercicio_x_evaluacion WHERE evaluacion = " + codigo;
        }

        query += " AND ejercicio=" + ejercicio;

        ResultSet r = this.execute_select(query);
        r.next();

        return r.getString("time_limit");

    }

    public void set_veredict(String veredict) throws Exception {
        String query = "UPDATE lms_envio SET resultado = '" + veredict + "' WHERE id = " + envio;
        System.out.println("el resultado del envio " + this.envio + " es " + veredict);
        this.execute_DML(query);
        //System.out.println("insertando en valor en evaluado...");
        query = "INSERT INTO lms_envio_evaluado VALUES('" + envio + "')";
        this.execute_DML(query);

    }

    public void set_message(String message) throws Exception {
        message = message.replace('\'', ' '); // clean the message        
        String query = "UPDATE lms_envio SET mensaje = '" + message + "' WHERE id = " + envio;
        this.execute_DML(query);

    }

    public void set_execution_time(String veredict) throws Exception {
        String query = "UPDATE lms_envio SET tiempo_de_ejecucion = '" + veredict + "' WHERE id = " + envio;
        this.execute_DML(query);

    }

    private void close() {
        try {
            conexion.close();
        } catch (Exception e) {
            System.err.println("Error cerrando la conexion con la bd");
        }

    }

}
