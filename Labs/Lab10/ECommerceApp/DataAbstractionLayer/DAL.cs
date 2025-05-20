using System;
using System.Collections.Generic;
using ECommerceApp.Models;
using MySql.Data.MySqlClient;

namespace ECommerceApp.DataAbstractionLayer
{
    public class DAL
    {
        private string connString = "datasource=127.0.0.1;port=3306;username=root;password=;database=ecommerce;";
        private MySqlConnection conn;

        public DAL()
        {
            this.conn = new MySqlConnection(connString);
            this.conn.Open();
        }
        
        public List<Product> GetProductsFromCategory(int categoryId)
        {
            List<Product> products = new List<Product>();

            try
            {
                MySqlCommand cmd = new MySqlCommand();
                cmd.Connection = this.conn;
                cmd.CommandText = "select * from product where categoryId=" + categoryId;
                MySqlDataReader myreader = cmd.ExecuteReader();

                while (myreader.Read())
                {
                    Product product = new Product();
                    product.Id = myreader.GetInt32("id");
                    product.Name = myreader.GetString("name");
                    product.Price = myreader.GetInt32("price");
                    product.CategoryId = myreader.GetInt32("categoryId");
                    products.Add(product);
                }
                myreader.Close();
            }
            catch (MySqlException ex)
            {
                Console.Write(ex.Message);
            }
            return products;
        }

        public List<Category> GetCategories()
        {
            List<Category> categories = [];

            try
            {
                MySqlCommand cmd = new();
                cmd.Connection = this.conn;
                cmd.CommandText = "select * from category";
                MySqlDataReader reader = cmd.ExecuteReader();

                while (reader.Read())
                {
                    Category category = new();
                    category.Id = reader.GetInt32("id");
                    category.Name = reader.GetString("name");
                    categories.Add(category);
                }

                reader.Close();
            }
            catch (MySqlException ex)
            {
                Console.Write(ex.Message);
            }

            return categories;
        }

        public int GetTotalProductsByCategory(int categoryId)
        {
            int totalPages = 0;

            try
            {
                MySqlCommand cmd = new();
                cmd.Connection = this.conn;
                cmd.CommandText = "select count(*) from product where categoryId=" + categoryId;

                MySqlDataReader reader = cmd.ExecuteReader();

                while (reader.Read())
                {
                    totalPages = reader.GetInt32(0);
                }

                reader.Close();
            }
            catch (MySqlException ex)
            {
                Console.Write(ex.Message);
            }

            return totalPages;
        }

        public Product GetProduct(int productId)
        {
            Product product = new();

            try
            {
                MySqlCommand cmd = new MySqlCommand();
                cmd.Connection = this.conn;
                cmd.CommandText = "select * from product where id=" + productId;
                MySqlDataReader myreader = cmd.ExecuteReader();

                while (myreader.Read())
                {
                    product.Id = myreader.GetInt32("id");
                    product.Name = myreader.GetString("name");
                    product.Price = myreader.GetInt32("price");
                    product.CategoryId = myreader.GetInt32("categoryId");
                }
                myreader.Close();
            }
            catch (MySqlException ex)
            {
                Console.Write(ex.Message);
            }
            return product;
        }

        public void CreateProduct(string productName, int price, string categoryName)
        {
            int categoryId = this.GetCategoryId(categoryName);

            try
            {
                MySqlCommand cmd = new MySqlCommand();
                cmd.Connection = this.conn;
                cmd.CommandText = "insert into product (name, price, categoryId) values ('" + productName +"', " + price + ", " + categoryId +")";
                cmd.ExecuteNonQuery();
            }
            catch (MySqlException ex)
            {
                Console.Write(ex.Message);
            }
        }

        private int GetCategoryId(string categoryName)
        {
            int categoryId = 0;

            try
            {
                MySqlCommand cmd = new MySqlCommand();
                cmd.Connection = this.conn;
                cmd.CommandText = "select id from category where name='" + categoryName + "'";
                MySqlDataReader myreader = cmd.ExecuteReader();

                while (myreader.Read())
                {
                    categoryId = myreader.GetInt32("id");
                }
                myreader.Close();
            }
            catch (MySqlException ex)
            {
                Console.Write(ex.Message);
            }

            return categoryId;
        }

        public string GetCategoryName(int categoryId)
        {
            string categoryName = "";

            try
            {
                MySqlCommand cmd = new MySqlCommand();
                cmd.Connection = this.conn;
                cmd.CommandText = "select name from category where id='" + categoryId + "'";
                MySqlDataReader myreader = cmd.ExecuteReader();

                while (myreader.Read())
                {
                    categoryName = myreader.GetString("name");
                }
                myreader.Close();
            }
            catch (MySqlException ex)
            {
                Console.Write(ex.Message);
            }

            return categoryName;
        }

        public void DeleteProduct(int productId)
        {
            MySqlCommand cmd = new();
            cmd.Connection = this.conn;
            cmd.CommandText = "delete from product where id = " + productId;
            cmd.ExecuteNonQuery();
        }

        public void UpdateProduct(int productId, string productName, int price, string categoryName)
        {
            int categoryId = this.GetCategoryId(categoryName);

            try
            {
                MySqlCommand cmd = new MySqlCommand();
                cmd.Connection = this.conn;
                cmd.CommandText = "update product set name='" + productName + "', price=" + price + ", categoryId=" + categoryId + " where id = " + productId;
                cmd.ExecuteNonQuery();
            }
            catch (MySqlException ex)
            {
                Console.Write(ex.Message);
            }
        }
    }
}
        