import { Component, OnInit } from '@angular/core';
import { NgFor, NgIf } from '@angular/common';

import { GenericService } from '../generic.service';
import { Product } from '../model/product';
import { Category } from '../model/category';
import { response } from 'express';

@Component({
    selector: 'app-products',
    imports: [NgFor, NgIf],
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

    visibleDetails = true;

    formMessage: string = '';

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

    deleteProduct(product: Product | undefined): void {
        if (product) {
            this.genericService.deleteProduct(product).subscribe();
            if (this.selectedCategory) {
              this.getProductsByCategory(this.currentPage, this.selectedCategory);
            }
            this.selectedProduct = undefined;
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
    }
}
