#!/usr/bin/env python3
"""
TipTap PHP Documentation Scraper
Scrapes TipTap PHP/Laravel documentation from tiptap.dev
"""

import requests
from bs4 import BeautifulSoup
import os
import time
import json
from urllib.parse import urljoin, urlparse
import re

class TipTapScraper:
    def __init__(self, base_url="https://tiptap.dev"):
        self.base_url = base_url
        self.visited_urls = set()
        self.docs_folder = "tiptapdocs"
        self.session = requests.Session()
        self.session.headers.update({
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        })
        
    def create_docs_folder(self):
        """Create documentation folder if it doesn't exist"""
        os.makedirs(self.docs_folder, exist_ok=True)
        
    def get_page_content(self, url):
        """Fetch page content with error handling"""
        try:
            print(f"Fetching: {url}")
            response = self.session.get(url, timeout=10)
            response.raise_for_status()
            return response.text
        except requests.RequestException as e:
            print(f"Error fetching {url}: {e}")
            return None
    
    def extract_main_content(self, soup):
        """Extract main content from TipTap documentation page"""
        # Try to find main content area
        main_content = soup.find('main') or soup.find('article') or soup.find('div', class_=re.compile('content|docs|documentation'))
        
        if not main_content:
            # Fallback to body
            main_content = soup.find('body')
        
        if main_content:
            # Remove script and style tags
            for script in main_content(["script", "style", "nav", "header", "footer"]):
                script.decompose()
            
            return main_content.get_text(separator='\n', strip=True)
        
        return soup.get_text(separator='\n', strip=True)
    
    def scrape_php_docs(self):
        """Scrape PHP-specific documentation pages"""
        php_pages = [
            "/docs/editor/getting-started/install/php",
            "/docs/editor/getting-started/install",
            "/docs/editor/getting-started/configure",
            "/docs/editor/getting-started/install/alpine",
            "/docs/editor/getting-started/install/vanilla",
            "/docs/editor/getting-started/install/cdn",
        ]
        
        all_content = []
        
        for page_path in php_pages:
            url = urljoin(self.base_url, page_path)
            if url in self.visited_urls:
                continue
                
            self.visited_urls.add(url)
            content = self.get_page_content(url)
            
            if content:
                soup = BeautifulSoup(content, 'html.parser')
                title = soup.find('title')
                title_text = title.get_text() if title else page_path
                
                main_content = self.extract_main_content(soup)
                
                all_content.append({
                    'url': url,
                    'title': title_text,
                    'content': main_content
                })
                
                print(f"[OK] Scraped: {title_text}")
                time.sleep(1)  # Be respectful
        
        return all_content
    
    def save_to_markdown(self, content_list, filename="tiptap-php-documentation.md"):
        """Save scraped content to markdown file"""
        filepath = os.path.join(self.docs_folder, filename)
        
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write("# TipTap PHP/Laravel Documentation\n\n")
            f.write("This documentation was scraped from [tiptap.dev](https://tiptap.dev)\n\n")
            f.write("---\n\n")
            
            for item in content_list:
                f.write(f"## {item['title']}\n\n")
                f.write(f"**Source:** [{item['url']}]({item['url']})\n\n")
                f.write("---\n\n")
                f.write(f"{item['content']}\n\n")
                f.write("---\n\n")
        
        print(f"\n[OK] Documentation saved to: {filepath}")
    
    def run(self):
        """Main scraper execution"""
        print("Starting TipTap PHP Documentation Scraper...\n")
        self.create_docs_folder()
        
        content_list = self.scrape_php_docs()
        
        if content_list:
            self.save_to_markdown(content_list)
            print(f"\n[OK] Successfully scraped {len(content_list)} pages")
        else:
            print("\n[ERROR] No content was scraped")

if __name__ == "__main__":
    scraper = TipTapScraper()
    scraper.run()
