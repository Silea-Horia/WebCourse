using ECommerceApp.DataAbstractionLayer;
using ECommerceApp.Models;
using Microsoft.AspNetCore.Mvc;

namespace ECommerceApp.Controllers
{
    public class AccountController : Controller
    {
        public IActionResult Index()
        {
            return View();
        }

        [HttpPost]
        public IActionResult Login(string username, string password)
        {
            DAL dal = new DAL();
            User? user = dal.GetUser(username, password);

            if (user != null)
            {
                HttpContext.Session.SetString("username", user.Username);
                return RedirectToAction("Index", "Main");
            }

            ViewBag.Error = "Invalid username or password";
            return View();
        }

        public IActionResult Logout()
        {
            HttpContext.Session.Clear();
            return RedirectToAction("Login", "Account");
        }
    }
}
