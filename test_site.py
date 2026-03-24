#!/usr/bin/env python3
import requests
import sys
import time

BASE_URL = "http://localhost:8080"

def test_page(path, expected_status=200):
    url = f"{BASE_URL}{path}"
    try:
        r = requests.get(url, timeout=5)
        status = r.status_code
        if status == expected_status:
            print(f"✅ {path} -> {status}")
            return True
        else:
            print(f"❌ {path} -> {status} (expected {expected_status})")
            return False
    except Exception as e:
        print(f"❌ {path} -> ERROR: {e}")
        return False

def test_api():
    """Тестирование API ajax.php"""
    url = f"{BASE_URL}/ajax.php?action=get_feedback"
    try:
        r = requests.get(url, timeout=5)
        if r.status_code == 200:
            data = r.json()
            if data.get('success') is not None:
                print(f"✅ API /ajax.php -> OK")
                return True
        print(f"❌ API /ajax.php -> {r.status_code}")
        return False
    except Exception as e:
        print(f"❌ API /ajax.php -> ERROR: {e}")
        return False

def main():
    print("=" * 50)
    print("Запуск тестирования сайта Кулинарная книга")
    print("=" * 50)
    
    # Ждем пока контейнеры поднимутся
    print("Ожидание запуска сервисов...")
    time.sleep(5)
    
    # Список страниц для проверки
    pages = [
        "/index.html",
        "/receipes2.php",
        "/your_receipe.php",
        "/contact.html",
        "/styles_index2.css",
        "/script.js"
    ]
    
    results = []
    for page in pages:
        results.append(test_page(page))
    
    # Проверка API
    results.append(test_api())
    
    # Итог
    print("\n" + "=" * 50)
    if all(results):
        print("✅ ВСЕ ТЕСТЫ ПРОЙДЕНЫ УСПЕШНО")
        sys.exit(0)
    else:
        print("❌ НЕКОТОРЫЕ ТЕСТЫ НЕ ПРОШЛИ")
        sys.exit(1)

if __name__ == "__main__":
    main()