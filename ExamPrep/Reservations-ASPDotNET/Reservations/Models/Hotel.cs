namespace Reservations.Models
{
    public class Hotel
    {
        public int HotelID { get; set; }
        public string HotelName { get; set; }
        public string Date { get; set; }
        public string City { get; set; }
        public int AvailableRooms { get; set; }
    }
}
