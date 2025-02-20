# Use official Python base image
FROM cubesdoo/php-fpm:7.4

# Set the working directory inside the container
WORKDIR /opt

#RUN apt-get -y update
RUN apt-get -y install python3-pip python3-venv

RUN python3 -m venv /usr/local/python/
# Ensure pip is installed (in case it's missing)
#RUN python3 -m ensurepip --upgrade

# Upgrade pip to the latest version
RUN /usr/local/python/bin/python3 -m pip install --upgrade pip

# Install numpy and faiss
RUN /usr/local/python/bin/pip3 install --use-pep517 numpy faiss-cpu

# Optional: If you need GPU support, install faiss-gpu instead:
# RUN pip install numpy faiss-gpu

# Set the default command (optional)
#CMD ["python3"]
