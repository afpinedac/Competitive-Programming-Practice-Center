
import java.util.*;

public class Test {

  public static void main(String[] args) {
    Scanner sc = new Scanner(System.in);
    HashMap<Integer, HashSet<Integer>> hm = new HashMap<Integer, HashSet<Integer>>();
    int g = 0;
    HashSet<Integer> students = new HashSet<Integer>();
    for (int i = 0; i < 5; i++) {
      int e = sc.nextInt();
      hm.put(e, new HashSet<>());
      for (int j = 0; j < e; j++) {
        int ced = sc.nextInt();
        students.add(ced);
        hm.get(e).add(ced);
      }
    }

    for (int s : students) {
      boolean all = true;
      for (Map.Entry<Integer, HashSet<Integer>> data : hm.entrySet()) {
        if (!data.getValue().contains(s)) {
          all = false;
          break;
        }
      }
      if (all) {
        g++;
      }
    }

    System.out.println(g == 0 ? "No hay ganadores" : (int) (Math.floor(1000000 / g)));

  }

}
