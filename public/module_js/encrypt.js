class Encoder {
    encode(data) {
        const timestamp = Date.now();
        const randomSalt = Math.random().toString(36).substring(2, 8);
        
        const payload = {
            d: data,        
            t: timestamp,   
            r: randomSalt   
        };
        
        const jsonString = JSON.stringify(payload);
        return btoa(unescape(encodeURIComponent(jsonString)));
    }
    
    decode(encodedString) {
        try {
            const jsonString = decodeURIComponent(escape(atob(encodedString)));
            const payload = JSON.parse(jsonString);
            
            return payload.d;
        } catch {
            return null;
        }
    }
}

export {Encoder};