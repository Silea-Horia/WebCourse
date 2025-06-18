import { Component, OnInit } from '@angular/core';
import { CommonModule, NgFor, NgIf } from '@angular/common';

import { GenericService } from '../generic.service';
import { Product } from '../model/product';
import { Category } from '../model/category';
import { response } from 'express';

@Component({
    selector: 'app-products',
    imports: [CommonModule],
    templateUrl: './products.component.html',
    styleUrl: './products.component.css'
})
export class ProductsComponent implements OnInit {
    products : Product[] = [];
    selectedProduct?: Product = undefined;

    categories: Category[] = [];
    selectedCategory?: Category;
    currentPage: number = 1;
    totalPages: number = 1;

    shoppingCart: {[key: number]: {product: Product, amount: number}} = {};
    cartTotal: number = 0;

    visibleDetails = true;

    formMessage: string = '';

    confirmation: boolean = false;

    constructor(private genericService : GenericService) {}

    ngOnInit(): void {
        this.getCategories();
    }

    getCategories(): void {
        this.genericService.fetchCategories()
            .subscribe(categories => this.categories = categories);
    }

    onSelect(category: Category): void {
        console.log("selected " + category.name);
        if (this.selectedCategory == category) {
            this.selectedCategory = undefined;
        } else {
            this.selectedCategory = category;
        }
        this.currentPage = 1;
        this.getProductsByCategory(this.currentPage, this.selectedCategory);
    }

    getProductsByCategory( page: number, category?: Category): void {
        if (category === undefined) {
            this.products = [];
            return;
        }
        this.genericService.fetchProducts(category, page)
            .subscribe(
                (response) => {
                    this.products = response.products;
                    this.totalPages = response.totalPages;
                }
            );
    }

    selectProduct(product: Product): void {
        this.selectedProduct = product;
    }

    createProduct(name: string, price: string, category: string) {
        console.log("creating " + name);
        this.genericService.createProduct(name, price, category).subscribe({
            next: (response) => {
                if (response.success) {
                  this.formMessage = 'Product created successfully!';
                  if (this.selectedCategory) {
                    this.getProductsByCategory(this.currentPage, this.selectedCategory);
                  }
                  this.selectedProduct = undefined;
                } else {
                  this.formMessage = `Create failed: ${response.error || 'Unknown error'}`;
                }
              },
              error: (error) => {
                this.formMessage = 'Create failed: Request error.';
                console.error('Error creating product:', error);
              }
        });
    }

    updateProduct(product: Product | undefined, newName: string, newPrice: string, newCategory: string): void {
        if (!product) {
            this.formMessage = 'No product selected.';
            return;
          }
          this.genericService.updateProduct(product, newName, newPrice, newCategory)
            .subscribe({
              next: (response) => {
                if (response.success) {
                  this.formMessage = 'Product updated successfully!';
                  if (this.selectedCategory) {
                    this.getProductsByCategory(this.currentPage, this.selectedCategory);
                    if (this.shoppingCart[product.id]) {
                        this.shoppingCart[product.id].product.name = newName;
                        this.shoppingCart[product.id].product.price = parseInt(newPrice);
                        this.shoppingCart[product.id].product.category = newCategory;
                        this.computeCartTotal();
                    }
                  }
                  this.selectedProduct = undefined;
                } else {
                  this.formMessage = `Update failed: ${response.error || 'Unknown error'}`;
                }
              },
              error: (error) => {
                this.formMessage = 'Update failed: Request error.';
                console.error('Error updating product:', error);
              }
            });
    }

    askDelete() {
        this.confirmation = true;
    }

    cancelDelete() {
        this.confirmation = false;
    }

    deleteProduct(product: Product | undefined): void {
        this.confirmation = false;
        if (product) {
            this.genericService.deleteProduct(product).subscribe();
            if (this.selectedCategory) {
              this.getProductsByCategory(this.currentPage, this.selectedCategory);
            }
            this.selectedProduct = undefined;
            if (this.shoppingCart[product.id]) {
                delete this.shoppingCart[product.id];
                this.computeCartTotal();
            }
        }
    }

    nextPage(): void {
        this.currentPage++;
        this.getProductsByCategory(this.currentPage, this.selectedCategory);
    }

    prevPage(): void {
        this.currentPage--;
        this.getProductsByCategory(this.currentPage, this.selectedCategory);
    }

    minimize(): void {
        this.visibleDetails = false;
    }

    resetForm() {
        this.selectedProduct = undefined;
        this.formMessage = '';
    }

    addToShoppingCart(product: Product) {
        if (this.shoppingCart[product.id]) {
            this.shoppingCart[product.id].amount++;
        } else {
            this.shoppingCart[product.id] = {product: product, amount: 1};
        }
        this.computeCartTotal();
    }

    removeFromShoppingCart(product: Product) {
        if (this.isProductInShoppingCart(product)) {
            this.shoppingCart[product.id].amount--;
            if (this.shoppingCart[product.id].amount == 0) {
                delete this.shoppingCart[product.id];
            }
            this.computeCartTotal();
        }
    }

    isProductInShoppingCart(product: Product): boolean {
        return this.shoppingCart[product.id] != undefined;
    }

    computeCartTotal() {
        this.cartTotal = 0;
        for (let key in this.shoppingCart) {
            this.cartTotal += this.shoppingCart[key].amount * this.shoppingCart[key].product.price;
        }
    }
}
