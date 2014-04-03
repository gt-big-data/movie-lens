from mrjob.job import MRJob
import heapq


class MovieNeighbour(MRJob):

    def mapper(self, _, line):
        user, movie, rating, _ = line.split("::")
        rating = float(rating)
        user = int(user)
        movie = int(movie)
        if(rating>=3):
            yield user, (movie, rating)

    def reducer(self, user, moviesRatings):
        res = list(moviesRatings)
        yield user, res

    def mapper2(self, user, moviesRatings):
        k = -1
        for k in xrange(len(moviesRatings)):
            for i in xrange(len(moviesRatings)):
                if i < k:
                    small = min((moviesRatings[k][0], moviesRatings[i][0]))
                    big = max((moviesRatings[k][0], moviesRatings[i][0]))
                    dist = abs(moviesRatings[i][1]-moviesRatings[k][1])
                    yield (small, big), (dist, 1)

    def reduce_average_scores(self, movies, distances):
        total = 0.
        totalDistances = 0.
        for d, times in distances:
            totalDistances += d * times
            total += times

        n = 5
        avg = (totalDistances+n*2) / (total+n)
        yield movies[0], (avg, total, movies[1])
        yield movies[1], (avg, total, movies[0])
    
    def mapper_movieCouple(self, movies, avgDist):
        yield movies, avgDist

    def reducer_getBest(self, movie, distNeighbour):
           best_movies = heapq.nsmallest(5, distNeighbour, key=lambda x: x[0] )
           yield movie, [movie[2] for movie in best_movies]

    def steps(self):
        return [self.mr(mapper=self.mapper, reducer=self.reducer), self.mr(mapper=self.mapper2, reducer=self.reduce_average_scores), self.mr(mapper=self.mapper_movieCouple, reducer=self.reducer_getBest)]

if __name__ == '__main__':
    MovieNeighbour.run()
