using Microsoft.AspNetCore.Mvc;
using ECommerceApp.DataAbstractionLayer;
using ECommerceApp.Models;

namespace ECommerceApp.Controllers
{
    public class MainController : Controller
    {
        private static int itemsPerPage = 4;
        private static Dictionary<int, int> shoppingCart = new();
        public IActionResult Index()
        {
            if (HttpContext.Session.GetString("username") == null)
            {
                return RedirectToAction("Login", "Account");
            }
            return View("FilterProducts");
        }

        public string GetCategories()
        {
            DAL dal = new();
            List<Category> categories = dal.GetCategories();
            ViewData["categoryList"] = categories;

            string result = "";

            foreach (Category category in categories)
            {
                result += "<button onclick=\"setCategory(" + category.Id + ")\">" + category.Name + "</button>";
            }

            return result;
        }

        public List<string> GetRawCategories()
        {
            DAL dal = new();
            List<string> categories = dal.GetCategories().Select(category => category.Name).ToList();
            return categories;
        }

        public string GetProductsFromCategory()
        {
            int categoryId = int.Parse(Request.Query["categoryId"]);
            int page = int.Parse(Request.Query["page"]);

            DAL dal = new DAL();
            List<Product> products = dal.GetProductsFromCategory(categoryId);

            int pageIndex = page - 1;
            int start = pageIndex * itemsPerPage;
            int length = start + itemsPerPage > products.Count ? products.Count - start : itemsPerPage;

            products = products.Slice(start, length);
            ViewData["productList"] = products;

            string result = "<table><thead><th>Name</th><th>Price</th><th>Add To Cart</th><th>Delete</th></thead>";

            foreach (Product product in products)
            {
                result += "<tr><td onclick=\"selectProduct(" + product.Id + ")\">" + product.Name + "</td><td>" + product.Price + "</td><td><button onclick=\"addToCart(" + product.Id + ")\">+</button></td><td><button onclick=\"deleteProduct(" + product.Id + ")\">del</button></td></tr>";
            }

            result += "</table>";
            return result;
        }

        public int GetTotalPages()
        {
            int categoryId = int.Parse(Request.Query["categoryId"]);

            DAL dal = new();

            int totalProducts = dal.GetTotalProductsByCategory(categoryId);

            return (int)Math.Ceiling((double)totalProducts / itemsPerPage);
        }

        public void AddToCart()
        {
            int productId = int.Parse(Request.Query["productId"]);

            DAL dal = new();

            Product product = dal.GetProduct(productId);

            if (shoppingCart.ContainsKey(product.Id))
            {
                shoppingCart[product.Id]++;
            } 
            else
            {
                shoppingCart.Add(product.Id, 1);
            }
        }

        public string GetCart()
        {
            DAL dal = new();

            string response = "<table><thead><th>Name</th><th>Amount</th><th>Remove</th></thead>";
            
            foreach (var pair in shoppingCart)
            {
                Product product = dal.GetProduct(pair.Key);

                response += "<tr>";
                response += "<td>" + product.Name + "</td>";
                response += "<td>" + pair.Value + "</td>";
                response += "<td><button onclick=\"removeFromCart(" + product.Id + ")\">-</button></td>";
                response += "</tr>";
            }

            response += "</table>";
            return response;
        }

        public void RemoveFromCart(int productId)
        {
            if (shoppingCart.ContainsKey(productId))
            {
                shoppingCart[productId]--;
                if (shoppingCart[productId] == 0)
                {
                    shoppingCart.Remove(productId);
                }
            }
        }

        public string CreateProduct(string productName, string price, string categoryName)
        {
            DAL dal = new();
            try
            {
                foreach (char c in productName)
                {
                    if ((c < 'a' || c > 'z') && (c < 'A' || c > 'Z'))
                    {
                        return "error: product name must contain only letters";
                    }
                }

                int parsedPrice;
                if (!int.TryParse(price, out parsedPrice))
                {
                    return "error: price must be a number";
                }

                dal.CreateProduct(productName, parsedPrice, categoryName);
            }
            catch (Exception e)
            {
                return e.Message;
            }
            return "success";
        }

        public void DeleteProduct(int productId)
        {
            DAL dal = new();
            dal.DeleteProduct(productId);
            if (shoppingCart.ContainsKey(productId))
            {
                shoppingCart.Remove(productId);
            }
        }

        public String GetProductName(int productId)
        {
            DAL dal = new();
            return dal.GetProduct(productId).Name;
        }


        public int GetProductPrice(int productId)
        {
            DAL dal = new();
            return dal.GetProduct(productId).Price;
        }

        public string GetProductCategory(int productId)
        {
            DAL dal = new();
            return dal.GetCategoryName(dal.GetProduct(productId).CategoryId);
        }

        public string UpdateProduct(int productId, string productName, string price, string categoryName)
        {
            DAL dal = new();
            try
            {
                foreach (char c in productName)
                {
                    if ((c < 'a' || c > 'z') && (c < 'A' || c > 'Z'))
                    {
                        return "error: product name must contain only letters";
                    }
                }

                int parsedPrice;
                if (!int.TryParse(price, out parsedPrice))
                {
                    return "error: price must be a number";
                }

                dal.UpdateProduct(productId, productName, parsedPrice, categoryName);
            }
            catch (Exception e)
            {
                return e.Message;
            }
            return "success";
        }
    }
}
