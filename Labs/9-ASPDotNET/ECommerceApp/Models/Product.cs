namespace ECommerceApp.Models
{
    public class Product
    {
        private int id;
        private string name;
        private int price;
        private int categoryId;

        public int Id { get; set; }
        public string Name { get; set; }
        public int Price { get; set; }
        public int CategoryId { get; set; }
    }
}
