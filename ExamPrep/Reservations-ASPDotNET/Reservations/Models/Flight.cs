namespace Reservations.Models
{
    public class Flight
    {
        public int FlightID { get; set; }
        public string Date { get; set; }
        public string DestinationCity { get; set; }
        public int AvailableSeats { get; set; }
    }
}
