using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using Reservations.DataAbstractionLayer;
using Reservations.Models;

namespace Reservations.Controllers
{
    public class HomeController : Controller
    {
        private readonly ILogger<HomeController> _logger;

        public HomeController(ILogger<HomeController> logger)
        {
            _logger = logger;
        }

        public IActionResult Index()
        {
            return View();
        }

        public IActionResult Privacy()
        {
            return View();
        }

        [HttpPost]
        public IActionResult BeginReservation(string username, string date, string city)
        {
            HttpContext.Session.SetString("username", username);
            HttpContext.Session.SetString("date", date);
            HttpContext.Session.SetString("city", city);
            HttpContext.Session.SetString("reservations", "");

            return View();
        }

        [HttpGet]
        public IActionResult GetFlights()
        {
            DAL dal = new();

            ViewData["Page"] = "Flights";
            
            ISession session = HttpContext.Session;
            string date = session.GetString("date");
            string destinationCity = session.GetString("city");
            
            ViewData["Flights"] = dal.GetFlights(date, destinationCity);

            return View("BeginReservation");
        }

        [HttpGet]
        public IActionResult GetHotels()
        {
            DAL dal = new();

            ViewData["Page"] = "Hotels";

            ISession session = HttpContext.Session;
            string date = session.GetString("date");
            string destinationCity = session.GetString("city");

            ViewData["Hotels"] = dal.GetHotels(date, destinationCity);

            return View("BeginReservation");
        }

        [HttpPost]
        public IActionResult MakeReservation(int id, string type)
        {
            DAL dal = new();

            ISession session = HttpContext.Session;
            string person = session.GetString("username");

            long reservationId = dal.InsertReservation(person, type, id);

            string reservations = session.GetString("reservations");
            session.SetString("reservations", reservations + reservationId + ",");

            return View("BeginReservation");
        }

        [HttpGet]
        public IActionResult CancelReservations()
        {
            DAL dal = new();
            ISession session = HttpContext.Session;
            string[] ids = session.GetString("reservations").Split(",");

            foreach (string id in ids)
            {
                if (id != "")
                {
                    dal.DeleteReservation(Int32.Parse(id));
                }
            }

            session.SetString("reservations", "");

            return View("BeginReservation");
        }

        [ResponseCache(Duration = 0, Location = ResponseCacheLocation.None, NoStore = true)]
        public IActionResult Error()
        {
            return View(new ErrorViewModel { RequestId = Activity.Current?.Id ?? HttpContext.TraceIdentifier });
        }
    }
}
