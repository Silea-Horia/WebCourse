using Microsoft.CodeAnalysis.Elfie.Diagnostics;
using MySql.Data.MySqlClient;
using Reservations.Models;
using static System.Runtime.InteropServices.JavaScript.JSType;

namespace Reservations.DataAbstractionLayer
{
    public class DAL
    {
        private string connString = "datasource=127.0.0.1;port=3306;username=root;password=;database=reservations;";
        private MySqlConnection conn;

        public DAL()
        {
            conn = new MySqlConnection(connString);
            conn.Open();
        }

        public List<Flight> GetFlights(string date, string destinationCity)
        {
            List<Flight> flights = new List<Flight>();

            string sql = "select * from Flights where date = @date and destinationCity = @destinationCity";
            MySqlCommand cmd = new MySqlCommand(sql, conn);
            cmd.Parameters.AddWithValue("@date", date);
            cmd.Parameters.AddWithValue("@destinationCity", destinationCity);
            
            MySqlDataReader reader = cmd.ExecuteReader();

            while (reader.Read())
            {
                Flight flight = new Flight();
                flight.FlightID = reader.GetInt32("flightID");
                flight.Date = reader.GetDateTime("date").Date.ToShortDateString();
                flight.DestinationCity = reader.GetString("destinationCity");
                flight.AvailableSeats = reader.GetInt32("availableSeats");

                flights.Add(flight);
            }

            reader.Close();

            return flights.Where(flight => flight.AvailableSeats > 0).ToList();
        }

        public List<Hotel> GetHotels(string date, string city)
        {
            List<Hotel> hotels = new List<Hotel>();

            string sql = "select * from Hotels where date = @date and city = @city";
            MySqlCommand cmd = new MySqlCommand(sql, conn);
            cmd.Parameters.AddWithValue("@date", date);
            cmd.Parameters.AddWithValue("@city", city);

            MySqlDataReader reader = cmd.ExecuteReader();

            while (reader.Read())
            {
                Hotel hotel = new Hotel();
                hotel.HotelID = reader.GetInt32("hotelID");
                hotel.HotelName = reader.GetString("hotelName");
                hotel.Date = reader.GetDateTime("date").Date.ToShortDateString();
                hotel.City = reader.GetString("city");
                hotel.AvailableRooms = reader.GetInt32("availableRooms");

                hotels.Add(hotel);
            }

            reader.Close();

            return hotels.Where(hotel => hotel.AvailableRooms > 0).ToList();
        }

        public long InsertReservation(string person, string type, int idReservedSource)
        {
            string sql = "insert into Reservations (person, type, idReservedSource) values (@person, @type, @idReservedSource)";
            MySqlCommand cmd = new MySqlCommand(sql, conn);
            cmd.Parameters.AddWithValue("@person", person);
            cmd.Parameters.AddWithValue("@type", type);
            cmd.Parameters.AddWithValue("@idReservedSource", idReservedSource);

            cmd.ExecuteNonQuery();

            long id = cmd.LastInsertedId;

            string tableName = type + "s";
            string idColumn = type + "ID";
            string updateColumn = "available" + (type == "hotel" ? "Rooms" : "Seats");

            sql = "update " + tableName + " set " + updateColumn + " = " + updateColumn + " - 1 where " + idColumn + " = @id";
            cmd = new MySqlCommand(sql, conn);
            cmd.Parameters.AddWithValue("@id", idReservedSource);

            cmd.ExecuteNonQuery();

            return id;
        }

        public void DeleteReservation(int id)
        {
            string sql = "select * from Reservations where id = @id";
            MySqlCommand cmd = new MySqlCommand(sql, conn);
            cmd.Parameters.AddWithValue("@id", id);
            MySqlDataReader reader = cmd.ExecuteReader();
            reader.Read();
            int sourceId = reader.GetInt32("idReservedSource");
            string type = reader.GetString("type");
            reader.Close();

            sql = "delete from Reservations where id = @id";
            cmd = new MySqlCommand(sql, conn);
            cmd.Parameters.AddWithValue("@id", id);
            cmd.ExecuteNonQuery();

            string tableName = type + "s";
            string idColumn = type + "ID";
            string updateColumn = "available" + (type == "hotel" ? "Rooms" : "Seats");

            sql = "update " + tableName + " set " + updateColumn + " = " + updateColumn + " + 1 where " + idColumn + " = @id";
            cmd = new MySqlCommand(sql, conn);
            cmd.Parameters.AddWithValue("@id", sourceId);

            cmd.ExecuteNonQuery();
        }
    }
}
