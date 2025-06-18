using MySql.Data.MySqlClient;

namespace DotNET.DataAbstractionLayer
{
    public class DAL
    {
        // TODO CHANGE
        private string connString = "datasource=127.0.0.1;port=3306;username=root;password=;database=CHANGEME;";
        private MySqlConnection conn;

        public DAL()
        {
            conn = new MySqlConnection(connString);
            conn.Open();
        }
    }
}
